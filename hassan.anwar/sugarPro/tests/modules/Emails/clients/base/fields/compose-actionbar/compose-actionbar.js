describe("Emails.fields.compose-actionbar", function() {
    var app, field;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'field', 'compose-actionbar', 'Emails');
        SugarTest.loadComponent('base', 'field', 'fieldset');
        SugarTest.loadComponent('base', 'field', 'actiondropdown');
        SugarTest.testMetadata.set();

        field = SugarTest.createField("base", "compose-actionbar", "compose-actionbar", "edit", null, "Emails", null, null, true);
    });

    afterEach(function() {
        field.dispose();
        app.cache.cutAll();
        app.view.reset();
        field = null;
    });

    describe("handleButtonClick", function() {
        var $button1, $button2, $button3, triggerStub, triggerCode;

        beforeEach(function() {
            field.$el = $('<div></div>');
            $button1 = $('<a name="button1" data-event="foo">button1</a> ');
            field.$el.append($button1.get(0).outerHTML);
            $button2 = $('<a name="button2">button2</a> ');
            field.$el.append($button2.get(0).outerHTML);
            $button3 = $('<a id="noname">button3</a> ');
            field.$el.append($button3.get(0).outerHTML);

            triggerStub = sinon.stub(field.view.context, 'trigger', function(code) {
                triggerCode = code;
            });
        });

        afterEach(function() {
            triggerCode = null;
            triggerStub.restore();
        });

        it("should fire event specified by data-event if there", function() {
            var event = {'currentTarget': $button1.get(0)};
            field.handleButtonClick(event);
            expect(triggerCode).toEqual('foo');
        });

        it("should fire event specifying the name if no data-event", function() {
            var event = {'currentTarget': $button2.get(0)};
            field.handleButtonClick(event);
            expect(triggerCode).toEqual('actionbar:button2:clicked');
        });

        it("should fire default event if no data-event or name", function() {
            var event = {'currentTarget': $button3.get(0)};
            field.handleButtonClick(event);
            expect(triggerCode).toEqual('actionbar:button:clicked');
        });
    });
});
