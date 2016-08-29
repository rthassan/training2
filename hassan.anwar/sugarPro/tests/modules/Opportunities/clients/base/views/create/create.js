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

describe('Opportunities.Base.Views.Create', function() {
    var app, view, options;

    beforeEach(function() {
        app = SugarTest.app;
        options = {
            meta: {
                panels: [{
                    fields: [{
                        name: 'name'
                    },{
                        name: 'commit_stage',
                        label: 'LBL_COMMIT_STAGE'
                    }]
                }]
            }
        };

        SugarTest.testMetadata.init();
        SugarTest.loadComponent('base', 'view', 'record');
        SugarTest.loadComponent('base', 'view', 'create');
        SugarTest.testMetadata.set();

        SugarTest.seedMetadata(true, './fixtures');
        view = SugarTest.createView('base', 'Opportunities', 'create', options.meta, null, true);
    });

    afterEach(function() {
        sinon.sandbox.restore();
    });


    describe('hasUnsavedChanges', function() {
        beforeEach(function() {
            sinon.sandbox.stub(view, '_super', function() {
                return false;
            });
        });

        it('will return false from _super call', function() {
            view.viewBy = 'Opportunities';
            expect(view.hasUnsavedChanges()).toBeFalsy();
        });

        describe('when not using rlis', function() {
            beforeEach(function() {
                view.viewBy = 'Opportunities';
                sinon.sandbox.stub(view.context, 'getChildContext');
            });

            it('should not call getChildContext', function() {
                view.hasUnsavedChanges();
                expect(view.context.getChildContext).not.toHaveBeenCalled();
            });
        });

        describe('when using rlis', function() {
            beforeEach(function() {
                view.viewBy = 'RevenueLineItems';
            });

            it('will not call getChildContext when hasRLIAccess is false', function() {
                sinon.sandbox.stub(view.context, 'getChildContext');
                view.hasRliAccess = false;
                view.hasUnsavedChanges();
                expect(view.context.getChildContext).not.toHaveBeenCalled();
            });

            describe('and has access will call getChildContext', function() {
                it('and return true with a collection length > 1', function() {
                    sinon.sandbox.stub(view.context, 'getChildContext', function() {
                        return {
                            prepare: function() {},
                            get: function() {
                                return {
                                    length: 2
                                };
                            }
                        };
                    });
                    expect(view.hasUnsavedChanges()).toBeTruthy();
                });

                it('and return false with a collection length = 0', function() {
                    sinon.sandbox.stub(view.context, 'getChildContext', function() {
                        return {
                            prepare: function() {},
                            get: function() {
                                return {
                                    length: 0
                                };
                            }
                        };
                    });
                    expect(view.hasUnsavedChanges()).toBeFalsy();
                });

                it('and return false with no model changes', function() {
                    sinon.sandbox.stub(view.context, 'getChildContext', function() {
                        return {
                            prepare: function() {},
                            get: function() {
                                return {
                                    at: function() {
                                        return new app.Bean();
                                    },
                                    length: 1
                                };
                            }
                        };
                    });
                    expect(view.hasUnsavedChanges()).toBeFalsy();
                });

                it('and return false with no default data changes', function() {
                    var model = new app.Bean();
                    model.setDefault('name', 'test');

                    sinon.sandbox.stub(view.context, 'getChildContext', function() {
                        return {
                            prepare: function() {},
                            get: function() {
                                return {
                                    at: function() {
                                        return model;
                                    },
                                    length: 1
                                };
                            }
                        };
                    });
                    expect(view.hasUnsavedChanges()).toBeFalsy();
                });

                it('and return true with bean changes', function() {

                    var model = new app.Bean();
                    model.setDefault('name', 'test');
                    model.set('name', 'changed');

                    sinon.sandbox.stub(view.context, 'getChildContext', function() {
                        return {
                            prepare: function() {},
                            get: function() {
                                return {
                                    at: function() {
                                        return model;
                                    },
                                    length: 1
                                };
                            }
                        };
                    });
                    expect(view.hasUnsavedChanges()).toBeTruthy();
                });
            });
        });
    });

    describe('getCustomSaveOptions', function() {
        var opts;

        beforeEach(function() {
            opts = {
                success: function() {}
            };
        });

        afterEach(function() {
            opts = null;
        });

        it('createdModel should not be undefined', function() {
            view.getCustomSaveOptions(opts);
            expect(_.isUndefined(view.createdModel)).toBeFalsy();
        });
        it('listContext should not be undefined', function() {
            view.getCustomSaveOptions(opts);
            expect(_.isUndefined(view.listContext)).toBeFalsy();
        });
    });

    describe('_checkForRevenueLineItems', function() {
        var model, options = {};
        beforeEach(function() {
            sinon.sandbox.stub(view, 'showRLIWarningMessage');
            model = new Backbone.Model();
            view.listContext = new Backbone.Model();
        });
        afterEach(function() {
            view.listContext = undefined;
        });

        it('will not call showRLIWarningMessage due to not having access', function() {
            view.hasRliAccess = false;
            view._checkForRevenueLineItems(model, options);
            expect(view.showRLIWarningMessage).not.toHaveBeenCalled();
        });
        it('will not call showRLIWarningMessage due having added RLIs', function() {
            model.set('revenuelineitems', {create: ['one']});
            view._checkForRevenueLineItems(model, options);

            expect(view.showRLIWarningMessage).not.toHaveBeenCalled();
        });
        it('will call showRLIWarningMessage', function() {
            model.set('revenuelineitems', {});
            view._checkForRevenueLineItems(model, options);
            expect(view.showRLIWarningMessage).toHaveBeenCalled();
        });
    });

    describe('createLinkModel', function() {
        var parentModel, createBeanStub, relateFieldStub;

        beforeEach(function() {
            parentModel = new Backbone.Model({
                id: '101-model-id',
                name: 'parent product name',
                account_id: 'abc-111-2222',
                account_name: 'parent account name',
                assigned_user_name: 'admin'
            }),
            createBeanStub = sinon.sandbox.stub(app.data, 'createRelatedBean', function() {
               return new Backbone.Model();
            }),
            relateFieldStub = sinon.sandbox.stub(app.data, 'getRelateFields', function() {
                return [{
                    name: 'product_template_name',
                    rname: 'name',
                    id_name: 'product_template_id',
                    populate_list: {
                        account_id: 'account_id',
                        account_name: 'account_name',
                        assigned_user_name: 'user_name'
                    }
                }];
            });
        });
        afterEach(function() {
            parentModel = null;
        });

        it('should populate related fields when it creates linked record', function() {
            var newModel = view.createLinkModel(parentModel, 'blah');
            expect(newModel.get('product_template_id')).toBe(parentModel.get('id'));
            expect(newModel.get('product_template_name')).toBe(parentModel.get('name'));
            expect(newModel.get('account_id')).toBe(parentModel.get('account_id'));
            expect(newModel.get('account_name')).toBe(parentModel.get('account_name'));
            expect(newModel.get('user_name')).toBe(parentModel.get('assigned_user_name'));
        });
        it('should store the relate fields in default to keep the values when creating a new linked model', function() {
            var newModel = view.createLinkModel(parentModel, 'blah');
            expect(newModel.relatedAttributes['product_template_id']).toBe(parentModel.get('id'));
            expect(newModel.relatedAttributes['product_template_name']).toBe(parentModel.get('name'));
            expect(newModel.relatedAttributes['account_id']).toBe(parentModel.get('account_id'));
            expect(newModel.relatedAttributes['account_name']).toBe(parentModel.get('account_name'));
            expect(newModel.relatedAttributes['user_name']).toBe(parentModel.get('assigned_user_name'));
        });
    });
});
