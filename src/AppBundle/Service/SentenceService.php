<?php
namespace AppBundle\Service;

class SentenceService
{
    const MODE_INT = 1;
    const MODE_POT = 2;
    const MODE_BEH = 3;
    const MODE_THI = 4;

    const TYPE_BASELINE = 1;
    const TYPE_ACTUAL = 2;

    const RESP_YESNO = 1;
    const RESP_CUSTOM = 2;

    /** @var array */
    private $modes;

    private $types;

    private $responseTypes;

    public function __construct()
    {
        $this->modes = array(
            self::MODE_INT => 'Intelligence',
            self::MODE_POT => 'Potential',
            self::MODE_BEH => 'Behavior',
            self::MODE_THI => 'Thinking'
        );

        $this->types = array(
            self::TYPE_BASELINE => 'Baseline',
            self::TYPE_ACTUAL => 'Actual'
        );

        $this->responseTypes = array(
            self::RESP_YESNO => 'Yes/No',
            self::RESP_CUSTOM => 'Custom'
        );
    }

    /**
     * @param $modeId
     * @return mixed
     */
    public function getModeName($modeId)
    {
        return $this->modes[$modeId];
    }

    /**
     * @return array
     */
    public function getAllModes()
    {
        return $this->modes;
    }

    /**
     * @param $typeId
     * @return mixed
     */
    public function getTypeName($typeId)
    {
        return $this->types[$typeId];
    }

    /**
     * @return array
     */
    public function getAllTypes()
    {
        return $this->types;
    }

    /**
     * @param $responseType
     * @return mixed
     */
    public function getResponseTypeName($responseType)
    {
        return $this->responseTypes[$responseType];
    }

    /**
     * @return array
     */
    public function getAllResponseTypes()
    {
        return $this->responseTypes;
    }
}