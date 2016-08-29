describe("Activity Stream Omnibar View", function() {
    var view;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('activitystream-omnibar', 'view', 'base');
        SugarTest.testMetadata.set();

        view = SugarTest.createView('base', 'Cases', 'activitystream-omnibar');
        view.render();
    });

    afterEach(function() {
        sinon.collection.restore();
        view.dispose();
        SugarTest.testMetadata.dispose();
    });

    describe("toggleSubmitButton()", function() {
        var attachments = {};

        beforeEach(function() {
            view.getAttachments = function() {
                return attachments;
            }
        });

        afterEach(function() {
            view.getAttachments = null;
        });

        it('Should disable Submit button by default', function() {
            expect(view.$('.addPost').hasClass('disabled')).toBe(true);
        });

        it('Should enable Submit button when there is text inside the input area', function() {
            view.$('.sayit').text('foo bar');
            view.toggleSubmitButton();
            expect(view.$('.addPost').hasClass('disabled')).toBe(false);
        });

        it('Should disable Submit button when there are only spaces inside the input area', function() {
            view.$('.sayit').text('       ');
            view.toggleSubmitButton();
            expect(view.$('.addPost').hasClass('disabled')).toBe(true);
        });

        it('Should enable Submit button when an attachment is added', function() {
            view.toggleSubmitButton();

            attachments = {one:1};
            view.trigger('attachments:add');

            expect(view.$('.addPost').hasClass('disabled')).toBe(false);
            attachments = {};
        });

        it('Should disable Submit button when an existing attachment is removed', function() {
            attachments = {one:1};
            view.toggleSubmitButton();

            attachments = {};
            view.trigger('attachments:remove');

            expect(view.$('.addPost').hasClass('disabled')).toBe(true);
        });

        describe('_handleContentChange calls toggleSubmitButton', function() {
            var evt,
                stubGetPost;

            beforeEach(function() {
                evt = {
                    currentTarget: {
                        setAttribute: function(attr, val) {},
                        removeAttribute: function(attr) {}
                    }
                };
                stubGetPost = sinon.collection.stub(view, 'getPost');
            });

            var dataProvider = [
                {
                    message: 'should enable Submit button when _handleContentChange receives an event with content',
                    content: 'foo',
                    expected: false
                },
                {
                    message: 'should disable Submit button when _handleContentChange receives an event without content',
                    content: '',
                    expected: true
                }
            ];

            _.each(dataProvider, function(data) {
                it(data.message, function() {
                    evt.currentTarget.textContent = data.content;
                    stubGetPost.returns({value: data.content});
                    view._handleContentChange(evt);
                    expect(view.$('.addPost').hasClass('disabled')).toBe(data.expected);
                });
            });
        });
    });
});
