<?php
namespace AppBundle\Command;

use AppBundle\Audio\Waveform;
use AppBundle\Service\RecordingService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueueCommand extends ContainerAwareCommand
{
    const RECORDING_DIR = '/home/jason/projects/SpeechAnalysis/web/recordings/';
    const OUTPUT_DIR = '/home/jason/projects/SpeechAnalysis/web/images/waveforms/';
    const DEFAULT_WIDTH = 1280;
    const DEFAULT_HEIGHT = 500;
    const DEFAULT_FOREGROUND = '#FF0000';
    const DEFAULT_BACKGROUND = '#FFFFFF';
    const DETAIL = 1;

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('process:queue')
            ->setDescription('Process queue of recorded statements');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $recordings = $this->em->getRepository('AppBundle:ProcessQueue')->findBy(array('dateProcessed' => null));
        foreach ($recordings as $recording) {
            $filename = $recording->getFilename();
            $outputFileSmall = $filename . '.png';
            $outputFileLarge = $filename . 'Large.png';
            $outputFileSpec = $filename . 'Spec.png';
            $this->generateWaveform($filename, self::OUTPUT_DIR . $outputFileSmall);
            $this->generateWaveform($filename, self::OUTPUT_DIR . $outputFileLarge, 2000, 750);
            $this->generateSpectrogram($filename, self::OUTPUT_DIR . $outputFileSpec);
            $recording->setDateProcessed(new \DateTime());
            $rec = $recording->getRecording();
            $rec->setStatus(RecordingService::STATUS_PENDING_ANALYSIS);
            $rec->setWaveformImage($outputFileSmall);
            $rec->setWaveFilename($outputFileLarge);
            $rec->setSpectrographImage($outputFileSpec);
            $this->em->persist($recording);
            $this->em->persist($rec);
            $this->em->flush();
        }
    }

    private function generateSpectrogram($filename, $output)
    {
        $file = self::RECORDING_DIR . $filename . '.wav';
        $cmd = "sox --ignore-length $file -n spectrogram -o $output -t 'Natural Intelligence Genetic Analysis' -l";
        exec($cmd);
    }

    private function generateWaveform($filename, $output, $width = 1000, $height = 300)
    {
        $file = self::RECORDING_DIR . $filename . '.wav';
        $cmd = 'ffmpeg -loglevel -8 -i ' . $file . ' -ac 1 -filter:a aresample=8000 -map 0:a -c:a pcm_s16le -f data - |';
        $cmd.= 'gnuplot -e "set terminal png size '.$width.','.$height.'; set output \''. $output .'\'; unset key; set grid; plot \'<cat\' binary filetype=bin format=\'%int16\' endian=little array=1:0 with lines;"';
        exec($cmd);
    }

//    private function generateWaveForm($filename)
//    {
//        $file = self::RECORDING_DIR . $filename . '.wav';
//        $img = false;
//        $width = self::DEFAULT_WIDTH;
//        $height = self::DEFAULT_HEIGHT;
//        $background = self::DEFAULT_BACKGROUND;
//        $draw_flat = true;
//        $wav = 1;
//        list($r, $g, $b) = $this->html2rgb(self::DEFAULT_FOREGROUND);
//        $handle = fopen($file, "r");
//        // wav file header retrieval
//        $heading[] = fread($handle, 4);
//        $heading[] = bin2hex(fread($handle, 4));
//        $heading[] = fread($handle, 4);
//        $heading[] = fread($handle, 4);
//        $heading[] = bin2hex(fread($handle, 4));
//        $heading[] = bin2hex(fread($handle, 2));
//        $heading[] = bin2hex(fread($handle, 2));
//        $heading[] = bin2hex(fread($handle, 4));
//        $heading[] = bin2hex(fread($handle, 4));
//        $heading[] = bin2hex(fread($handle, 2));
//        $heading[] = bin2hex(fread($handle, 2));
//        $heading[] = fread($handle, 4);
//        $heading[] = bin2hex(fread($handle, 4));
//
//        // wav bitrate
//        $peek = hexdec(substr($heading[10], 0, 2));
//        $byte = $peek / 8;
//
//        // checking whether a mono or stereo wav
//        $channel = hexdec(substr($heading[6], 0, 2));
//
//        $ratio = ($channel == 2 ? 40 : 80);
//        // start putting together the initial canvas
//        // $data_size = (size_of_file - header_bytes_read) / skipped_bytes + 1
//        $data_size = floor((filesize($file) - 44) / ($ratio + $byte) + 1);
//        $data_point = 0;
//
//        // now that we have the data_size for a single channel (they both will be the same)
//        // we can initialize our image canvas
//        if (!$img) {
//            // create original image width based on amount of detail
//            // each waveform to be processed with be $height high, but will be condensed
//            // and resized later (if specified)
//            $img = imagecreatetruecolor($data_size / self::DETAIL, $height);
//
//            // fill background of image
//            if (self::DEFAULT_BACKGROUND == "") {
//                // transparent background specified
//                imagesavealpha($img, true);
//                $transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
//                imagefill($img, 0, 0, $transparentColor);
//            } else {
//                list($br, $bg, $bb) = $this->html2rgb($background);
//                imagefilledrectangle($img, 0, 0, (int) ($data_size / self::DETAIL), $height, imagecolorallocate($img, $br, $bg, $bb));
//            }
//        }
//
//        while (!feof($handle) && $data_point < $data_size) {
//            if ($data_point++ % self::DETAIL == 0) {
//                $bytes = array();
//
//                // get number of bytes depending on bitrate
//                for ($i = 0; $i < $byte; $i++) {
//                    $bytes[$i] = fgetc($handle);
//                }
//
//                switch($byte) {
//                    // get value for 8-bit wav
//                    case 1:
//                        $data = $this->findValues($bytes[0], $bytes[1]);
//                        break;
//                    // get value for 16-bit wav
//                    case 2:
//                        if(ord($bytes[1]) & 128) {
//                            $temp = 0;
//                        } else {
//                            $temp = 128;
//                        }
//                        $temp = chr((ord($bytes[1]) & 127) + $temp);
//                        $data = floor($this->findValues($bytes[0], $temp) / 256);
//                        break;
//                }
//
//                // skip bytes for memory optimization
//                fseek($handle, $ratio, SEEK_CUR);
//
//                // draw this data point
//                // relative value based on height of image being generated
//                // data values can range between 0 and 255
//                $v = (int) ($data / 255 * $height);
//
//                // don't print flat values on the canvas if not necessary
//                if (!($v / $height == 0.5 && !$draw_flat)) {
//                    // draw the line on the image using the $v value and centering it vertically on the canvas
//                    imageline(
//                        $img,
//                        // x1
//                        (int)($data_point / self::DETAIL),
//                        // y1: height of the image minus $v as a percentage of the height for the wave amplitude
//                        $height * $wav - $v,
//                        // x2
//                        (int)($data_point / self::DETAIL),
//                        // y2: same as y1, but from the bottom of the image
//                        $height * $wav - ($height - $v),
//                        imagecolorallocate($img, $r, $g, $b)
//                    );
//                }
//            } else {
//                // skip this one due to lack of detail
//                fseek($handle, $ratio + $byte, SEEK_CUR);
//            }
//        }
//
//        // close and cleanup
//        fclose($handle);
//        // want it resized?
//        if ($width) {
//            // resample the image to the proportions defined in the form
//            $rimg = imagecreatetruecolor($width, $height);
//            // save alpha from original image
//            imagesavealpha($rimg, true);
//            imagealphablending($rimg, false);
//            // copy to resized
//            imagecopyresampled($rimg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
//            imagepng($rimg, '/home/jason/test.png', 0);
//            imagedestroy($rimg);
//        } else {
//            imagepng($img);
//        }
//
//        imagedestroy($img);
//    }

    private function findValues($byte1, $byte2)
    {
        $byte1 = hexdec(bin2hex($byte1));
        $byte2 = hexdec(bin2hex($byte2));
        return ($byte1 + ($byte2*256));
    }

    /**
     * Great function slightly modified as posted by Minux at
     * http://forums.clantemplates.com/showthread.php?t=133805
     */
    private function html2rgb($input)
    {
        $input = ($input[0] == "#") ? substr($input, 1,6) : substr($input, 0,6);
        return array(
            hexdec(substr($input, 0, 2)),
            hexdec(substr($input, 2, 2)),
            hexdec(substr($input, 4, 2))
        );
    }
}