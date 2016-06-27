var NatIntelli = NatIntelli || {};
NatIntelli.App = NatIntelli.App || {};

NatIntelli.App.RpcClient = (function($) {
    function RpcClient(url)
    {
        this.url = url || '/jsonrpc/';
    }

    RpcClient.prototype.call = function(method, params, success, fail) {
        var data = (success) ? { id: new Date().getTime() } : {};

        success = success || function() {};
        fail = fail || function() {};

        return $.ajax({
            url: this.url,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify($.extend({}, data, {
                jsonrpc: '2.0',
                method: method,
                params: params
            })),
            success: function(data) {
                if (data === undefined) {
                    success.apply(this, []);
                } else if (data !== undefined && data !== null && data.result !== undefined) {
                    success.apply(this, [data.result]);
                } else {
                    fail.apply(this, [data]);
                }
            },
            error: function(data) {
                fail.apply(this, [data]);
            }
        });
    };

    return RpcClient;
})(jQuery);