describe("image field", function() {

    var app, field, model;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('image', 'field', 'base', 'edit');
        SugarTest.loadHandlebarsTemplate('image', 'field', 'base', 'detail');
        SugarTest.testMetadata.set();
        SugarTest.app.data.declareModels();

        model = app.data.createBean('Contacts');
        field = SugarTest.createField("base", "test_image_upload", "image", "detail", { required: true }, "Contacts", model);
    });

    afterEach(function() {
        app.cache.cutAll();
        app.view.reset();
        Handlebars.templates = {};
        model = null;
        if (field.view) {
            field.view.dispose();
        }
        field = null;
    });

    describe("image", function() {

        it("should define widget height and width on render", function() {
            field.render();
            expect(field.width).toEqual(42);
            expect(field.height).toEqual(42);

            field = SugarTest.createField("base", "test_image_search", "image", "detail", {width: "120"}, "Contacts", model);
            field.render();
            expect(field.width).toEqual(120);
            expect(field.height).toEqual(120);

            field = SugarTest.createField("base", "test_image_search", "image", "detail", {height: "160"}, "Contacts", model);
            field.render();
            expect(field.width).toEqual(160);
            expect(field.height).toEqual(160);

            field = SugarTest.createField("base", "test_image_search", "image", "detail", {width: "180", height: 100}, "Contacts", model);
            field.render();
            expect(field.width).toEqual(180);
            expect(field.height).toEqual(100);
        });

        it("should resize height", function() {
            field.render();
            field.resizeHeight(200);
            expect(field.$(".image_field").height()).toEqual(200);
            field.resizeHeight(100);
            expect(field.$(".image_field").height()).toEqual(100);

            //Must add 18 for the edit button on edit views !
            field = SugarTest.createField("base", "test_image_upload", "image", "edit", {}, "Contacts", model);
            field.render();
            field.$('.image_btn').css({height: '15px'});
            field.resizeHeight(200);
            expect(field.$(".fa-plus").css('lineHeight')).toEqual(200 - 15 + 'px');
            field.$('.image_btn').css({height: '12px'});
            field.resizeHeight(100);
            expect(field.$(".fa-plus").css('lineHeight')).toEqual(100 - 12 + 'px');
        });

        it("should resize width", function() {
            field.render();
            field.resizeWidth(100);
            expect(field.$(".image_field").css('width')).toEqual('100px');
            field.resizeWidth(200);
            expect(field.$(".image_field").css('width')).toEqual('200px');
        });

        it("should only bind data change when not in edit or create", function() {
            var stub = sinon.stub(app.view.Field.prototype, 'bindDataChange');
            field.view = new app.view.View({});
            field.view.name = 'edit';
            field.bindDataChange();
            expect(stub).not.toHaveBeenCalled();
            stub.reset();

            field.view.name = 'create';
            field.bindDataChange();
            expect(stub).not.toHaveBeenCalled();
            stub.reset();

            field.view.name = 'detail';
            field.view.options = {viewName: 'edit'};
            field.bindDataChange();
            expect(stub).toHaveBeenCalled();
            stub.reset();

            field.view.name = 'detail';
            field.view.options = {viewName: 'detail'};
            field.bindDataChange();
            expect(stub).toHaveBeenCalled();
            stub.reset();

            field.view.name = 'detail';
            field.view.options = {viewName: 'detail'};
            field.view.action = 'edit';
            field.bindDataChange();
            expect(stub).not.toHaveBeenCalled();

            stub.restore();
        });

        it("should trigger change with a param for the record view", function() {
            var triggerSpy = sinon.spy(model, "trigger");
            var attrStub = sinon.stub(jQuery, 'attr');
            field.model.uploadFile = function() {};
            var uploadFileStub = sinon.stub(field.model, "uploadFile", function(fieldName, $files, callbacks, options) {
                // Force production code's success hook to fire passing our fake meta
                callbacks.success({
                    test_image_upload: {
                        guid: "image-guid"
                    }
                });
            });
            field.selectImage();
            expect(triggerSpy).toHaveBeenCalledWith("change", "image");
            triggerSpy.restore();
            attrStub.restore();
        });

    });

    describe("image upload", function() {

        it("should format value", function() {
            expect(field.format("")).toEqual("");
            expect(field.format("filename3.jpg")).not.toEqual("");
            expect(field.format("filename3.jpg")).not.toEqual("filename3.jpg");
        });

        it("make an api call to delete the image", function() {
            var confirmStub = sinon.stub(window, "confirm", function() {
                return true
            });
            var deleteStub = sinon.stub(app.api, "call");
            var renderSpy = sinon.spy(field, "render");
            $("<a></a>").addClass("delete").appendTo(field.$el);
            field.undelegateEvents();
            field.delegateEvents();

            field.$(".delete").trigger("click");
            expect(deleteStub).toHaveBeenCalled();

            field.preview = true;
            field.$(".delete").trigger("click");
            expect(renderSpy).toHaveBeenCalled();
            deleteStub.restore();
            renderSpy.restore();
            confirmStub.restore();
        });

        it("should not render on input change because we cannot set value of an input type file", function() {
            var renderSpy = sinon.spy(field, "render");
            $('<input type="text">').appendTo(field.$el);

            field.$("input").val("test");
            expect(renderSpy).not.toHaveBeenCalled();
            renderSpy.restore();
        });
    });

    describe("image validation", function() {

        it("should return an error if field is required but no image is selected", function() {
            $('<input>').attr('type', 'file').appendTo(field.$el);
            var callback = sinon.stub();
            field._doValidateImageField(null, {}, callback);

            expect(callback).toHaveBeenCalled();
            expect(callback.lastCall.args[2][field.name]).toBeDefined();
            expect(callback.lastCall.args[2][field.name].required).toBeTruthy();
        });
    });
});
