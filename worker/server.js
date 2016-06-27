/**
 * Created by noamc on 8/31/14.
 */
var binaryServer = require('binaryjs').BinaryServer;
var wav = require('wav');

var args = process.argv.slice(2);
var port = args[0];
var outputFile = args[1];

if ((port === undefined || port === null)
    || (outputFile === undefined || outputFile === null)) {
    console.error('No port or filename specified');
    process.exit(1);
}

var fs = require('fs');
if (!fs.existsSync("recordings"))
    fs.mkdirSync("recordings");
    
var server = binaryServer({port: port});
console.log('starting server on port: ' + port);
console.log('saving data to: ' + outputFile);
server.on('connection', function(client) {
    console.log("new connection...");
    var fileWriter = null;

    client.on('stream', function(stream, meta) {
        console.log("Stream Start@" + meta.sampleRate +"Hz");
        var fileName = "recordings/"+ outputFile  + ".wav";
        console.log(fileName);
        fileWriter = new wav.FileWriter(fileName, {
            channels: 1,
            sampleRate: meta.sampleRate,
            bitDepth: 16
        });

        stream.pipe(fileWriter);
    });

    client.on('close', function() {
        if (fileWriter != null) {
            fileWriter.end();
        }
        console.log("Connection Closed");
        process.exit();
    });
});
