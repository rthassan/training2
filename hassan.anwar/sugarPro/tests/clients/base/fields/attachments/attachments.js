describe("Base.Field.Attachments", function() {
    var app, field, apiCallStub;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'field', 'attachments');
        SugarTest.loadHandlebarsTemplate('attachments', 'field', 'base', 'edit');
        SugarTest.testMetadata.set();

        apiCallStub = sinon.stub(app.api, 'call');

        field = SugarTest.createField("base", "attachments", "attachments", "edit");
    });

    afterEach(function() {
        apiCallStub.restore();
        app.cache.cutAll();
        app.view.reset();
        field.dispose();
    });

    describe("render", function() {
        it("should wrap the hidden field in select2 with empty data by default", function() {
            field.render();
            expect(field.$node.select2('data')).toEqual([]);
        });

        it("should have select2 init with data from model if present", function() {
            var expectedAttachments = [
                {id:'123',nameForDisplay:'foo'},
                {id:'456',nameForDisplay:'bar'}
            ];
            field.model.set('attachments', expectedAttachments);
            field.render();
            expect(field.$node.select2('data')).toEqual(expectedAttachments);
        });
    });

    describe("Adding Attachments", function() {
        beforeEach(function() {
            field.render();
        });

        it("attachment:add should add attachment to select2 and model", function() {
            var expectedAttachment = {id:'123',nameForDisplay:'foo'};
            field.context.trigger('attachment:add', expectedAttachment);
            expect(field.$node.select2('data')).toEqual([expectedAttachment]);
            expect(field.model.get('attachments')).toEqual([expectedAttachment]);
        });

        it("addAttachmentToContainer should add attachment to select2 only", function() {
            var expectedAttachment = {id:'789',nameForDisplay:'baz'};
            field.addAttachmentToContainer(expectedAttachment);
            expect(field.$node.select2('data')).toEqual([expectedAttachment]);
            expect(field.model.has('attachments')).toBe(false);
        });

        it("should replace existing attachment if replaceId is specified", function() {
            var placeholder = {id:'ph',nameForDisplay:'placeholder'};
            var replacement = {id:'123',nameForDisplay:'foo.txt',replaceId:'ph'};
            field.addAttachmentToContainer(placeholder);
            field.context.trigger('attachment:add', replacement);
            expect(field.$node.select2('data')).toEqual([replacement]);
            expect(field.model.get('attachments')).toEqual([replacement]);
        });
    });

    describe("Removing Attachments", function() {
        var attachment1, attachment2, triggerStub;

        beforeEach(function() {
            attachment1 = {id:'123', type:'foo', nameForDisplay:'bar', tag:'baz'};
            attachment2 = {id:'456', type:'bar', nameForDisplay:'foo'};
            field.render();
            field.addAttachment(attachment1);
            field.addAttachment(attachment2);
            triggerStub = sinon.stub(field.context, 'trigger');
        });

        afterEach(function() {
            triggerStub.restore();
        });

        // test to be fixed under MAR-1493
        it("should remove first attachment from list when its x is clicked", function() {
            field.$('.select2-search-choice-close:first').click();
            expect(field.$node.select2('data')).toEqual([attachment2]);
            expect(field.model.get('attachments')).toEqual([attachment2]);
        });

        it("should fire remove event when attachment is removed", function() {
            field.$('.select2-search-choice-close:last').click();
            expect(triggerStub.calledWith('attachment:bar:remove',attachment2)).toBe(true);
        });

        it("should remove any attachments with given tag and fire appropriate trigger", function() {
            field.removeAttachmentsByTag('baz');
            expect(field.$node.select2('data')).toEqual([attachment2]);
            expect(field.model.get('attachments')).toEqual([attachment2]);
            expect(triggerStub.calledWith('attachment:foo:remove',attachment1)).toBe(true);
        });

        it("should remove any attachments with given id and fire appropriate trigger", function() {
            field.removeAttachmentsById('456');
            expect(field.$node.select2('data')).toEqual([attachment1]);
            expect(field.model.get('attachments')).toEqual([attachment1]);
            expect(triggerStub.calledWith('attachment:bar:remove',attachment2)).toBe(true);
        });

        it("should call api to remove file on backend if uploaded attachment removed", function() {
            var attachment = {id:'42', type:'upload', nameForDisplay:'foo'};
            field.addAttachment(attachment);
            triggerStub.restore();
            field.removeAttachmentsById('42');
            expect(apiCallStub.lastCall.args[0]).toEqual('delete');
            expect(apiCallStub.lastCall.args[1]).toMatch(/.*\/Mail\/attachment\/42/);
        });

        it('should cancel the request', function() {
            var stub = sinon.stub(app.api, 'abortRequest');

            field.requests['upload1'] = 13;
            field.notifyAttachmentRemoved({id: 'upload1', type: 'upload'});

            expect(stub).toHaveBeenCalled();
            stub.restore();
        });

        it('should not cancel the request', function() {
            var stub = sinon.stub(app.api, 'abortRequest');

            field.notifyAttachmentRemoved({id: 'upload1', type: 'upload'});

            expect(stub).not.toHaveBeenCalled();
            stub.restore();
        });
    });

    describe("Uploading Attachments", function() {
        var getFileInputValStub, alertStub, loggerStub, attachmentsBefore, expectedAttachmentsBefore;

        beforeEach(function() {
            field.render();
            expectedAttachmentsBefore = [{id:'upload1', nameForDisplay:'foo.txt', showProgress:true}];
            getFileInputValStub = sinon.stub(field, 'getFileInputVal', function() {return 'C:\\fakepath\\foo.txt'});
            alertStub = sinon.stub(app.alert, 'show');
            loggerStub = sinon.stub(app.logger, 'error');
            apiCallStub.restore();
        });

        afterEach(function() {
            getFileInputValStub.restore();
            alertStub.restore();
            loggerStub.restore();
        });

        it("should set placeholder when uploading file and replace it on success", function() {
            var mockUploadResult = {guid:'123', nameForDisplay:'foo.txt'},
                expectedAttachmentsAfterAfter = [{id:'123', nameForDisplay:'foo.txt', type:'upload'}];

            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                attachmentsBefore = field.$node.select2('data');
                callbacks.success(mockUploadResult);
            });
            field.uploadFile();
            expect(attachmentsBefore).toEqual(expectedAttachmentsBefore);
            expect(field.$node.select2('data')).toEqual(expectedAttachmentsAfterAfter);
        });

        it("should alert and remove placeholder if no result guid on success", function() {
            var mockUploadResult = {nameForDisplay:'foo.txt'};

            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                attachmentsBefore = field.$node.select2('data');
                callbacks.success(mockUploadResult);
            });
            field.uploadFile();
            expect(attachmentsBefore).toEqual(expectedAttachmentsBefore);
            expect(field.$node.select2('data')).toEqual([]);
            expect(alertStub.lastCall.args[0]).toEqual('upload_error');
        });

        it("should alert and remove placeholder if error returned from API", function() {
            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                callbacks.error({});
            });
            field.uploadFile();
            expect(attachmentsBefore).toEqual(expectedAttachmentsBefore);
            expect(field.$node.select2('data')).toEqual([]);
            expect(alertStub.lastCall.args[0]).toEqual('upload_error');
        });

        it('should pass the oauth token in the query string', function() {
            var apiGetTokenStub, url;

            apiGetTokenStub = sinon.stub(app.api, 'getOAuthToken').returns('foo')
            apiCallStub = sinon.stub(app.api, 'call');

            field.uploadFile();
            url = apiCallStub.lastCall.args[1];

            expect(url).toMatch(/&oauth_token=foo/);

            apiGetTokenStub.restore();
        });

        it('should use the default error message when alerting an error', function() {
            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                callbacks.error({});
            });
            field.uploadFile();
            expect(alertStub.lastCall.args[1].messages).toEqual('LBL_EMAIL_ATTACHMENT_UPLOAD_FAILED');
        });

        it('should use a specified error message when alerting an error', function() {
            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                callbacks.error({error_message: 'custom error message'});
            });
            field.uploadFile();
            expect(alertStub.lastCall.args[1].messages).toEqual('custom error message');
        });

        it('should not display an error message if the request was aborted', function() {
            var stub = sinon.stub(field, 'handleUploadError');

            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                callbacks.error({errorThrown: 'abort'});
            });
            field.uploadFile();
            expect(stub).not.toHaveBeenCalled();
            stub.restore();
        });

        it('should clear the file field when the upload request completes', function() {
            var stub = sinon.stub(field, 'clearFileInputVal');

            apiCallStub = sinon.stub(app.api, 'call', function(method, url, data, callbacks) {
                callbacks.complete();
            });
            field.uploadFile();
            expect(stub).toHaveBeenCalled();
            stub.restore();
        });
    });
});
