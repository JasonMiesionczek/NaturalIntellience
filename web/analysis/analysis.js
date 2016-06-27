import {inject} from 'aurelia-framework';
import {RpcClient} from 'common/RpcClient';

@inject(RpcClient)
export class Analysis {
    constructor(rpc) {
        this.rpc = new RpcClient();
        this.getRecordings();
        this.recordings = [];
    }

    getRecordings() {
        this.rpc.call('analysis.endpoint:getRecordings',
            {analysisId: "10", userId:"1"}
        ).then(data => {
            this.recordings = data.result;
        });
    }

}
