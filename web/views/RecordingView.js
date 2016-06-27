var NatIntelli = NatIntelli || {};
NatIntelli.App = NatIntelli.App || {};

NatIntelli.App.RecordingView = (function(Client) {
    'use strict';

    function RecordingView(analysisId, apiToken, currentRecording)
    {
        var self = this;
        this.recordings = [];
        this.currentRecordingId = currentRecording;
        this.currentRecording = 0;
        this.totalRecordings = 0;
        this.recordingProgress = 0;
        this.analysisId = analysisId;
        this.apiToken = apiToken;
        this.client = new Client();
        this.binaryClient = null;
        this.recorder = null;
        this.context = null;
        this.timerInterval = null;
        this.currentTimer = 15;
        this.contextSampleRate = (new AudioContext()).sampleRate;
        this.resampleRate = 8000;
        this.worker = new Worker('/lib/resampler-worker.js');
        this.bStream = null;
        this.worker.postMessage({cmd:"init", from: this.contextSampleRate, to: this.resampleRate});
        this.worker.addEventListener('message', function(e) {
            if (self.bStream && self.bStream.writable) {
                self.bStream.write(convertFloat32ToInt16(e.data.buffer));
            }
        });

        this.getRecordings(function(data) {
            $.each(data, function(idx, recording) {
                 if (recording.id === self.currentRecordingId) {
                     self.currentRecording = idx;
                 }
            });
            self.totalRecordings = data.length;
            self.setCurrentRecording(data);
            $('#stop-rec-btn').prop('disabled', true);
        });

        $(document.body).on('click', '#start-rec-btn', function() {
            // first need to fire off the back-end start recording call
            $(this).prop('disabled', true);
            $('#stop-rec-btn').prop('disabled', false);
            var params = {
                recordingId: self.recordings[self.currentRecording].id,
                apiToken: self.apiToken
            };
            self.client.call(
                'recording.endpoint:start',
                params,
                function(data) {
                    // if we get here, the recording is ready to start from the back-end
                    self.startStream(data, function() {
                        $(document.body).css('border', '5px solid #f00');
                        self.currentTimer = 15;
                        self.timerInterval = setInterval(function() {self.updateTimer(self); }, 1000);
                    });
                },
                function(data) {
                    alert(data.error.message + ': ' + data.error.data);
                }
            );
        });

        $(document.body).on('click', '#stop-rec-btn', function() {
            $("#recordingTimer").hide();
            clearInterval(self.timerInterval);
            self.stopRecording(self);
        });
    }

    RecordingView.prototype.stopRecording = function(self) {
        $('#stop-rec-btn').prop('disabled', true);
        self.binaryClient.close();
        var params = {
            recordingId: self.recordings[self.currentRecording].id,
            apiToken: self.apiToken
        };
        self.client.call(
            'recording.endpoint:stop',
            params,
            function(data) {
                // when this recording is stopped, time to move on to the next
                self.nextRecording();
                $(document.body).css('border', 'none');
            },
            function(data) {
                alert(data.error.message + ': ' + data.error.data);
            }
        );
    };

    RecordingView.prototype.updateTimer = function(self) {
        if (self.currentTimer >= 0) {
            $("#recordingTimer").show();
            $("#recordingTimer").html(self.currentTimer);
            self.currentTimer--;
        } else {
            $("#recordingTimer").hide();
            clearInterval(self.timerInterval);
            self.stopRecording(self);
        }
    };

    RecordingView.prototype.nextRecording = function() {
        if (this.currentRecording < this.recordings.length) {
            this.currentRecording++;
            this.setCurrentRecording();
            $('#start-rec-btn').prop('disabled', false);
        } else {
            window.location.href = '/results';
        }
    };

    RecordingView.prototype.startStream = function(data, cb) {
        var self = this;
        var parser = location;
        var hostname = parser.hostname;
        this.binaryClient = new BinaryClient('ws://'+hostname+':' + data.port);
        this.binaryClient.on('open', function () {
            self.bStream = self.binaryClient.createStream({sampleRate: self.resampleRate});
        });

        if (this.context) {
            this.recorder.connect(this.context.destination);
            if (typeof cb === 'function') {
                cb();
            }
            return;
        }

        var session = {
            audio: true,
            video: false
        };

        navigator.getUserMedia(session, function (stream) {
            self.context = new AudioContext();
            var audioInput = self.context.createMediaStreamSource(stream);
            var bufferSize = 0;
            self.recorder = self.context.createScriptProcessor(bufferSize, 1, 1);
            self.recorder.onaudioprocess = function (e) {
                var left = e.inputBuffer.getChannelData(0);
                self.worker.postMessage({cmd: "resample", buffer: left});
                drawBuffer(left);
            };
            audioInput.connect(self.recorder);
            self.recorder.connect(self.context.destination);
            if (typeof cb === 'function') {
                cb();
            }
        }, function (e) {

        });
    };

    function drawBuffer(data) {
        var canvas = document.getElementById("canvas"),
            width = canvas.width,
            height = canvas.height,
            context = canvas.getContext('2d');

        context.clearRect (0, 0, width, height);
        var step = Math.ceil(data.length / width);
        var amp = height / 2;
        for (var i = 0; i < width; i++) {
            var min = 1.0;
            var max = -1.0;
            for (var j = 0; j < step; j++) {
                var datum = data[(i * step) + j];
                if (datum < min)
                    min = datum;
                if (datum > max)
                    max = datum;
            }
            context.fillRect(i, (1 + min) * amp, 1, Math.max(1, (max - min) * amp));
        }
    }

    function convertFloat32ToInt16(buffer) {
        var l = buffer.length;
        var buf = new Int16Array(l);
        while (l--) {
            buf[l] = Math.min(1, buffer[l]) * 0x7FFF;
        }
        return buf.buffer;
    }

    RecordingView.prototype.getRecordings = function(cb)
    {
        var self = this;
        var params = {
            analysisId: this.analysisId,
            apiToken: this.apiToken
        };

        this.client.call(
            'analysis.endpoint:getRecordings',
            params,
            function(data) {
                self.recordings = data;
                if (typeof cb === 'function') {
                    cb(data);
                }
            },
            function(data) {
                alert(data.error.message);
            }
        );
    };

    RecordingView.prototype.getCurrentRecording = function(cb)
    {
        var params = {
            analysisId: this.analysisId,
            recordingId: this.recordings[this.currentRecording].id,
            apiToken: this.apiToken
        };

        this.client.call(
            'analysis.endpoint:getRecording',
            params,
            function(data) {
                self.recordings[self.currentRecording] = data;
                if (typeof cb === 'function') {
                    cb(data);
                }
            },
            function(data) {
                alert(data.error.message);
            }
        );

        return this.recordings[this.currentRecording];
    };

    RecordingView.prototype.setCurrentRecording = function()
    {
        var self = this;
        var params = {
            analysisId: this.analysisId,
            recordingId: this.recordings[this.currentRecording].id,
            apiToken: this.apiToken,
            num: this.currentRecording
        };

        this.client.call(
            'analysis.endpoint:setCurrentRecording',
            params,
            function(data) {
                var $statement = $('#statement');
                var $progress = $('.progress-bar');
                var recording = self.recordings[self.currentRecording];
                $statement.html(recording.statement);
                self.recordingProgress = Math.round((self.currentRecording / self.totalRecordings) * 100);
                $progress.css('width', self.recordingProgress + '%');
                $progress.html(self.recordingProgress + '%' + ' (' + self.currentRecording + '/' + self.totalRecordings + ')');
            },
            function(data) {
                alert(data.error.message);
            }
        );

    };

    return RecordingView;
})(NatIntelli.App.RpcClient);