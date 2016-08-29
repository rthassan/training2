describe('htmleditable_tinymce', function() {
    var field, sandbox;

    beforeEach(function() {
        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'field', 'htmleditable_tinymce');
        SugarTest.testMetadata.set();
    });

    afterEach(function() {
        SugarTest.testMetadata.dispose();
    });

    describe('edit view', function() {
        beforeEach(function() {
            var $textarea = $('<textarea class="htmleditable"></textarea>');
            sandbox = sinon.sandbox.create();
            field = SugarTest.createField('base', 'html_email', 'htmleditable_tinymce', 'edit');
            sandbox.stub(field, '_getHtmlEditableField', function() {
                return $textarea;
            });
            sandbox.stub(field, 'destroyTinyMCEEditor', $.noop());
        });

        afterEach(function() {
            field.dispose();
            sandbox.restore();
        });

        it('should render edit view not readonly view', function() {
            var edit = sandbox.spy(field, '_renderEdit'),
                view = sandbox.spy(field, '_renderView');
            sandbox.stub(field, 'initTinyMCEEditor', $.noop());
            field.render();

            expect(edit.calledOnce).toBeTruthy();
            expect(view.called).toBeFalsy();
        });

        it('should give access to TinyMCE config', function() {
            expect(field.getTinyMCEConfig()).toBeDefined();
        });

        it('should initialize TinyMCE editor when it doesn\'t exist', function() {
            var tinymceSpy = sandbox.spy($.fn, 'tinymce'),
                configSpy = sandbox.spy(field, 'getTinyMCEConfig');

            field.initTinyMCEEditor();

            expect(tinymceSpy.calledOnce).toBeTruthy();
            expect(configSpy.calledOnce).toBeTruthy();
        });

        it('should initialize TinyMCE editor with custom config options', function() {
            var tinymceSpy = sandbox.spy($.fn, 'tinymce'),
                configSpy = sandbox.spy(field, 'getTinyMCEConfig');

            field.initTinyMCEEditor({
                script_url: 'include/javascript/tiny_mce/tiny_mce.js',
                theme: 'advanced',
                skin: 'sugar7',
                plugins: 'style',
                entity_encoding: 'raw',
                theme_advanced_buttons1: 'code',
                theme_advanced_toolbar_location: 'top',
                theme_advanced_toolbar_align: 'left',
                theme_advanced_statusbar_location: 'bottom',
                theme_advanced_resizing: true,
                schema: 'html5'
            });

            expect(tinymceSpy.calledOnce).toBeTruthy();
            expect(configSpy.called).toBeTruthy();
        });

        it('should not initialize TinyMCE editor if it already exists', function() {
            var tinymceSpy = sandbox.spy($.fn, 'tinymce'),
                config = field.getTinyMCEConfig();
            config.setup = $.noop();

            field.initTinyMCEEditor(config);
            expect(tinymceSpy.calledOnce).toBeTruthy();

            field.render();
            expect(tinymceSpy.calledOnce).toBeTruthy();
        });

        it('setting a value to the model should also set the editor with that value', function() {
            var expectedValue = 'foo',
                setEditorContentSpy;

            field.render();
            setEditorContentSpy = sandbox.spy(field, 'setEditorContent');
            field.model.set(field.name, expectedValue);

            expect(setEditorContentSpy.withArgs(expectedValue).calledOnce).toBeTruthy();
        });
    });

    describe('readonly view', function() {
        beforeEach(function() {
            var $textarea = $('<iframe class="htmleditable" frameborder="0"></iframe>');
            sandbox = sinon.sandbox.create();
            field = SugarTest.createField('base', 'html_email', 'htmleditable_tinymce', 'detail');
            sandbox.stub(field, '_getHtmlEditableField', function() {
                return $textarea;
            });
            sandbox.stub(field, 'destroyTinyMCEEditor', $.noop());
        });

        afterEach(function() {
            field.dispose();
            sandbox.restore();
        });

        it('should render read view not edit view', function() {
            var edit = sandbox.spy(field, '_renderEdit'),
                view = sandbox.spy(field, '_renderView');

            field.render();

            expect(edit.called).toBeFalsy();
            expect(view.calledOnce).toBeTruthy();
        });

        it('should not return TinyMCE editor', function() {
            var tinymceSpy = sandbox.spy(field, 'initTinyMCEEditor');

            field.render();

            expect(tinymceSpy.called).toBeFalsy();
        });

        it('should set the value to the div if the model is changed', function() {
            var mock,
                expectedValue = 'foo';

            field.render();

            mock = sandbox.mock(field);
            mock.expects('setViewContent').once().withArgs(expectedValue);

            field.model.set(field.name, expectedValue);

            mock.verify();
        });
    });
});
