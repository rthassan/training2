describe('View.Fields.Base.SaveAndSendInvitesButtonField', function() {
    var app, event, field, sandbox;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'field', 'button');
        SugarTest.loadComponent('base', 'field', 'rowaction');
        SugarTest.loadComponent('base', 'field', 'save-and-send-invites-button');
        SugarTest.testMetadata.set();

        field = SugarTest.createField(
            'base',
            'save_button',
            'save-and-send-invites-button',
            'edit',
            {event: 'button:save_button:click'},
            'Meetings'
        );

        sandbox = sinon.sandbox.create();

        event = $.Event('click');
    });

    afterEach(function() {
        sandbox.restore();
        if (field) {
            field.dispose();
        }
        SugarTest.testMetadata.dispose();
        app.cache.cutAll();
        app.view.reset();
    });

    describe('when the save button is clicked', function() {
        it('should set send_invites to true and trigger record save', function() {
            sandbox.stub(field.view.context, 'trigger');

            field.rowActionSelect(event);

            expect(field.view.context.trigger.calledWith('button:save_button:click')).toBe(true);
            expect(field.model.get('send_invites')).toBe(true);
        });

        using('triggered events', ['error:validation', 'data:sync:complete'], function(trigger) {
            it('should unset send_invites for triggered events', function() {
                sandbox.stub(field.view.context, 'trigger');

                field.rowActionSelect(event);

                expect(field.view.context.trigger.calledWith('button:save_button:click')).toBe(true);
                expect(field.model.get('send_invites')).toBe(true);

                field.model.trigger(trigger);

                expect(field.model.get('send_invites')).toBe(undefined);
            });
        });
    });
});
