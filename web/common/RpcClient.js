import 'fetch';
import {inject} from 'aurelia-framework';
import {HttpClient, json} from 'aurelia-fetch-client';

@inject(HttpClient)
export class RpcClient {
    constructor(http) {
        this.http = new HttpClient();;
        this.http.configure(config => {
            config
                .useStandardConfiguration()
                .withDefaults({
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'Fetch'
                    }
            })
        });
    }

    call(method, params) {
        let rpc = {
            jsonrpc: "2.0",
            method: method,
            id: "foo",
            params: params
        };

        return this.http.fetch('/jsonrpc/',{
            method: 'post',
            body: json(rpc)
        }).then(response => response.json());
    }
}
