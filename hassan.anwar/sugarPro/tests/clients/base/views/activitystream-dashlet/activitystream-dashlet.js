/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

describe('Activity Stream Dashlet View', function() {
    var view,
        app;

    beforeEach(function() {
        app = SugarTest.app;
        SugarTest.testMetadata.init();
        SugarTest.loadHandlebarsTemplate('activitystream-dashlet', 'view', 'base');
        SugarTest.loadComponent('base', 'view', 'activitystream');
        SugarTest.loadComponent('base', 'view', 'activitystream-omnibar');
        SugarTest.testMetadata.set();

        var context = app.context.getContext({
            module: 'ActivityStream',
            layout: 'dashlet'
        });
        context.parent = app.context.getContext({
            module: 'Accounts',
            layout: 'record'
        });
        context.prepare();
        context.parent.prepare();

        var meta = {};

        var layout = app.view.createLayout({
            name: 'dashlet',
            context: context
        });


        SugarTest.loadPlugin('Dashlet');
        SugarTest.loadPlugin('Pagination');

        view = SugarTest.createView('base', 'ActivityStream', 'activitystream-dashlet', meta, context, null, layout);
        view.render();
    });

    afterEach(function() {
        sinon.collection.restore();
        view.dispose();
        SugarTest.testMetadata.dispose();
    });

    describe('renderPost()', function() {
        var model = {id: 'asdf',
            activity_type: 'post',
            data: {
                embeds: [{
                    type: 'video',
                    html: "<iframe width='200px' height='100px'></iframe>",
                    width: 200,
                    height: 100
                }]
            }
        };

        beforeEach(function() {
            view.renderedActivities = {};
        });

        afterEach(function() {
            view.renderedActivities = {};
        });

        it('should cache rendered activities', function() {
            view.model.set(model);
            var testView = view.renderPost(view.model);
            expect(testView.name).toEqual('activitystream');

            view.renderedActivities['asdf'].name = 'asdf';
            testView = view.renderPost(view.model);
            expect(testView.name).toEqual('asdf');
        });
    });

    describe('render()', function() {
        it('should skip render on pagination render updates', function() {
            expect(view.rendered).toBe(true);
        });
    });

    describe('loadData()', function() {
        var moduleMetaStub,
            templateStub;
        beforeEach(function() {
            moduleMetaStub = sinon.stub(app.metadata, 'getModule', function(module) {
                return {activityStreamEnabled: module === 'Contacts'};
            });
            templateStub = sinon.stub(app.template, 'get', function(name) {
                if (name === 'activitystream-dashlet.disabled') {
                    return 1;
                } else {
                    return 0;
                }
            });
            fetchStub = sinon.stub(view.collection, 'fetch', function(options) {
                if (options) {
                    view.optionsTest = options;
                }
            });
            view.activityStreamEnabled = true;
        });

        afterEach(function() {
            moduleMetaStub.restore();
            templateStub.restore();
            fetchStub.restore();
        });

        it('should load error template if metadata does not allow activity stream', function() {
            view.activityStreamEnabled = false;
            view.loadData();
            expect(view.template).toEqual(1);
        });

        it('should call fetch with the correct options', function() {
            view.context.parent.set('module', 'Contacts');
            view.loadData();
            expect(_.isFunction(view.optionsTest.endpoint)).toEqual(true);
            expect(_.isFunction(view.optionsTest.success)).toEqual(true);
        });
    });

    describe('disposeAllActivities()', function() {
        var model = {id: 'asdf',
            activity_type: 'post',
            data: {
                embeds: [{
                    type: 'video',
                    html: "<iframe width='200px' height='100px'></iframe>",
                    width: 200,
                    height: 100
                }]
            }
        };
        var dispose = function() {};

        it('should empty renderedActivities cache', function() {
            view.model.set(model);
            var testView = view.renderPost(view.model);
            expect(view.renderedActivities['asdf']).toBeDefined();

            view.disposeAllActivities();
            expect(view.renderedActivities['asdf']).not.toBeDefined();
        });

        it('should remove activities from components', function() {
            view._components = [];
            view._components.push({name: 'activitystream', dispose: dispose});
            view._components.push({name: 'activitystream', dispose: dispose});
            view._components.push({name: 'notactivitystream', dispose: dispose});
            view._components.push({name: 'notactivitystream', dispose: dispose});
            expect(view._components.length).toEqual(4);

            view.disposeAllActivities();
            expect(view._components.length).toEqual(2);
        });
    });
});
