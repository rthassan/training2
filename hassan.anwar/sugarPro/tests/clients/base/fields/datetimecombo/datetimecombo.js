describe('Base.Field.DateTimeCombo', function() {
    var app;

    beforeEach(function() {
        app = SugarTest.app;

        SugarTest.loadComponent('base', 'field', 'date');

        // FIXME: this should be removed when SC-2395 gets in since new
        // versions are capable of handling translations by themselves
        sinon.collection.stub(app.metadata, 'getStrings', function() {
            return {
                dom_cal_day_long: {0: '', 1: 'Sunday', 2: 'Monday', 3: 'Tuesday', 4: 'Wednesday', 5: 'Thursday', 6: 'Friday', 7: 'Saturday'},
                dom_cal_day_short: {0: '', 1: 'Sun', 2: 'Mon', 3: 'Tue', 4: 'Wed', 5: 'Thu', 6: 'Fri', 7: 'Sat'},
                dom_cal_month_long: {0: '', 1: 'January', 2: 'February', 3: 'March', 4: 'April', 5: 'May', 6: 'June', 7: 'July', 8: 'August', 9: 'September', 10: 'October', 11: 'November', 12: 'December'},
                dom_cal_month_short: {0: '', 1: 'Jan', 2: 'Feb', 3: 'Mar', 4: 'Apr', 5: 'May', 6: 'Jun', 7: 'Jul', 8: 'Aug', 9: 'Sep', 10: 'Oct', 11: 'Nov', 12: 'Dec'}
            };
        });
    });

    afterEach(function() {
        sinon.collection.restore();

        app.cache.cutAll();
        app.view.reset();
    });

    describe('verify options from field definitions are recognized and passed into timepicker', function() {
        var timeFieldOptions = [
            {key: 'disable_text_input', value: true, name: 'disableTextInput'},
            {key: 'disable_text_input', value: false, name: 'disableTextInput'},
            {key: 'scroll_default_now', value: true, name: 'scrollDefaultNow'},
            {key: 'scroll_default_now', value: false, name: 'scrollDefaultNow'},
            {key: 'step', value: 20, name: 'step'},
            {key: 'step', value: 55, name: 'step'}
        ];

        using('time field options', timeFieldOptions, function(option) {
            it('should use the ' + option.key + ' option from the field def when instantiating the timepicker', function() {
                var def, field, spy;
                def = {time: {}};
                def.time[option.key] = option.value;
                sinon.collection.stub(app.user, 'getPreference').withArgs('timepref').returns('h:ia');
                field = SugarTest.createField('base', 'time', 'datetimecombo', 'edit', def);
                spy = sinon.collection.spy();
                sinon.collection.stub(field, '$').withArgs(field.secondaryFieldTag).returns({
                    timepicker: function() {
                        spy(arguments);
                    }
                });
                field._setupTimePicker();
                expect(spy.args[0][0][0][option.name]).toBe(option.value);
                sinon.collection.restore();
                field.dispose();
            });
        });
    });

    describe('format', function() {
        beforeEach(function() {
            sinon.collection.spy(app, 'date');
            sinon.collection.spy(app.date, 'convertFormat');
            sinon.collection.spy(app.date.fn, 'format');
            sinon.collection.spy(app.date.fn, 'formatUser');

            sinon.collection.stub(app.user, 'getPreference')
                .withArgs('datepref').returns('d/m/Y')
                .withArgs('timepref').returns('h:ia');
        });

        it('should format according to user preferences for edit mode', function() {
            var field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit');
            field.action = 'edit';

            expect(field.format('1984-01-15 19:20')).toEqual({'date': '15/01/1984', 'time': '07:20pm'});
            expect(app.date).toHaveBeenCalled();
            expect(app.date.convertFormat.getCall(0)).toHaveBeenCalledWith('d/m/Y');
            expect(app.date.convertFormat.getCall(1)).toHaveBeenCalledWith('h:ia');
            expect(app.date.fn.format).toHaveBeenCalledTwice();

            field.dispose();
        });

        it('should format according to user preferences for detail mode', function() {
            var field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'detail');

            expect(field.format('1984-01-15 19:20:42')).toEqual('15/01/1984 07:20pm');
            expect(app.date).toHaveBeenCalled();
            expect(app.date.fn.formatUser).toHaveBeenCalled();

            field.dispose();
        });

        it('should return undefined if an invalid datetime is supplied', function() {
            var field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit');

            expect(field.format()).toBeUndefined();
            expect(field.format('1984-01-32 19:20:42')).toBeUndefined();

            field.dispose();
        });
    });

    describe('unformat', function() {
        var field;

        beforeEach(function() {
            sinon.collection.spy(app, 'date');
            sinon.collection.spy(app.date, 'convertFormat');
            sinon.collection.spy(app.date.fn, 'format');

            sinon.collection.stub(app.user, 'getPreference')
                .withArgs('datepref').returns('d/m/Y')
                .withArgs('timepref').returns('h:ia');

            field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit');
        });

        afterEach(function() {
            field.dispose();
        });

        it('should unformat based on user preferences and according to server format', function() {
            expect(field.unformat('15/01/1984 07:20pm')).toBe(app.date('1984-01-15 19:20').format());
            expect(app.date.convertFormat).toHaveBeenCalledWith('d/m/Y h:ia');
            expect(app.date.getCall(0).args[0]).toBe('15/01/1984 07:20pm');
            expect(app.date.getCall(0).args[2]).toBe(true);
            expect(app.date.fn.format).toHaveBeenCalled();
        });

        it('should return undefined if an invalid date is supplied', function() {
            expect(field.unformat()).toBeUndefined();
            expect(field.unformat('32/01/1984 19:20:42')).toBeUndefined();
        });

        it('should return \'\' if an empty string is supplied', function() {
            expect(field.unformat('')).toBe('');
        });
    });

    describe('defaults', function() {
        beforeEach(function() {
            SugarTest.testMetadata.init();
            SugarTest.loadHandlebarsTemplate('datetimecombo', 'field', 'base', 'edit');
            SugarTest.testMetadata.set();

            var tomorrow = new Date('Sun Jan 15 1984 19:20:42');

            sinon.collection.stub(app.date, 'parseDisplayDefault')
                .withArgs('every other week').returns(undefined)
                .withArgs('+1 day').returns(tomorrow);

            sinon.collection.stub(app.user, 'getPreference')
                .withArgs('datepref').returns('d/m/Y')
                .withArgs('timepref').returns('h:ia');
        });

        afterEach(function() {
            SugarTest.testMetadata.dispose();
            Handlebars.templates = {};
        });
        
        it('should use default value if model has none', function() {
            var expectedDate = app.date('1984-01-15 19:20').format(),
                fieldDef = {display_default: '+1 day'},
                field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit', fieldDef);

            field.render();

            expect(field.value).toEqual({'date': '15/01/1984', 'time': '07:20pm'});
            expect(field.model.get(field.name)).toBe(expectedDate);
            expect(field.model.getDefault(field.name)).toBe(expectedDate);

            field.dispose();
        });

        it('should not use default value if default value is invalid', function() {
            var fieldDef = {display_default: 'every other week'},
                field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit', fieldDef);

            field.render();

            expect(field.value).toBeNull();
            expect(field.model.get(field.name)).toBeUndefined();

            field.dispose();
        });

        it('should not use default value if model has a value', function() {
            var model = new app.data.createBean('Accounts', {datetimecombo: '1985-01-26 23:15:09'}),
                fieldDef = {display_default: '+1 day'},
                field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit', fieldDef, 'Accounts', model);

            field.render();

            expect(field.value).toEqual({'date': '26/01/1985', 'time': '11:15pm'});
            expect(field.model.get(field.name)).toBe('1985-01-26 23:15:09');

            field.dispose();
        });
    });

    describe('render', function() {
        var field;

        beforeEach(function() {
            SugarTest.testMetadata.init();
            SugarTest.loadHandlebarsTemplate('datetimecombo', 'field', 'base', 'edit');
            SugarTest.testMetadata.set();

            sinon.collection.stub(app.user, 'getPreference')
                .withArgs('datepref').returns('d/m/Y')
                .withArgs('timepref').returns('h:ia');

            field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'edit');
        });

        afterEach(function() {
            field.dispose();
            SugarTest.testMetadata.dispose();
            Handlebars.templates = {};
        });

        describe('edit', function() {
            it('should have both pickers defined only in edit mode', function() {
                field.render();

                expect(field.$(field.fieldTag).data('datepicker')).toBeDefined();
                expect(field.$(field.secondaryFieldTag).data('timepicker-settings')).toBeDefined();

                field.dispose();

                field = SugarTest.createField('base', 'datetimecombo', 'datetimecombo', 'detail');
                field.render();

                expect(field.$(field.fieldTag).data('datepicker')).toBeUndefined();
                expect(field.$(field.secondaryFieldTag).data('timepicker-settings')).toBeUndefined();
            });

            it('should update date value when time value changes', function() {
                var now = new Date('Sun Jan 15 1984 19:20:42'),
                    clock = sinon.useFakeTimers(now.getTime(), 'Date');

                field.render();

                var $d = field.$(field.fieldTag),
                    $t = field.$(field.secondaryFieldTag);

                expect($d.val()).toBe('');
                expect($t.val()).toBe('');
                expect(field.model.get(field.name)).toBeUndefined();

                $t.val('07:20pm').trigger('change');

                expect($d.val()).toBe('15/01/1984');
                expect(field.model.get(field.name)).toBe(app.date('1984-01-15 19:20').format());

                $t.val('').trigger('change');

                expect($d.val()).toBe('');
                expect(field.model.get(field.name)).toBe('');

                clock.restore();
            });

            it('should update time value when date value changes', function() {
                var now = new Date('Sun Jan 15 1984 19:20:42'),
                    clock = sinon.useFakeTimers(now.getTime(), 'Date');

                field.render();

                var $d = field.$(field.fieldTag),
                    $t = field.$(field.secondaryFieldTag);

                expect($d.val()).toBe('');
                expect($t.val()).toBe('');
                expect(field.model.get(field.name)).toBeUndefined();

                $d.val('15/01/1984').trigger('hide');

                expect($t.val()).toBe('07:20pm');
                expect(field.model.get(field.name)).toBe(app.date('1984-01-15 19:20').format());

                // FIXME: `hide` event is still triggered due to the way the
                // library works, this should be reviewed once SC-2395 gets in
                $d.val('').trigger('hide');

                expect($t.val()).toBe('');
                expect(field.model.get(field.name)).toBe('');

                clock.restore();
            });

            it('should not display 24:00 when given 24 hour time format', function() {
                var $timepickerList,
                    now = new Date('Sun Jan 15 1984 19:20:42'),
                    clock = sinon.useFakeTimers(now.getTime(), 'Date');

                sinon.collection.stub(field, 'getUserTimeFormat').returns('H:i');

                field.render();

                $timepickerList = field.$(field.secondaryFieldTag)
                    .focus()
                    .data('timepickerList');

                expect($timepickerList.find('li').length).toBe(96);
                expect($timepickerList.find('li:contains("24:00")').length).toBe(0);
                expect($timepickerList.find('li:contains("00:00")').length).toBe(1);

                clock.restore();
            });

            it('should not display 24.00 when given 24 hour time format', function() {
                var $timepickerList,
                    now = new Date('Sun Jan 15 1984 19:20:42'),
                    clock = sinon.useFakeTimers(now.getTime(), 'Date');

                sinon.collection.stub(field, 'getUserTimeFormat').returns('H.i');

                field.render();

                $timepickerList = field.$(field.secondaryFieldTag)
                    .focus()
                    .data('timepickerList');

                expect($timepickerList.find('li').length).toBe(96);
                expect($timepickerList.find('li:contains("24.00")').length).toBe(0);
                expect($timepickerList.find('li:contains("00.00")').length).toBe(1);

                clock.restore();
            });
        });

        describe('_enableDuration', function() {
            it('should add duration to the timepicker dropdown', function() {
                var $durationDropdown;

                field.def.time = {
                    duration: {
                        relative_to: 'foo'
                    }
                };
                field.name = 'bar';
                field.model.set({
                    foo: '2014-07-17T11:00',
                    bar: '2014-07-17T12:00'
                });

                field.render();
                field.$(field.secondaryFieldTag).focus();

                $durationDropdown = field.$(field.secondaryFieldTag).data().timepickerList;

                expect($durationDropdown.find('.ui-timepicker-duration').length).toBe(52);
                expect($durationDropdown.find('.ui-timepicker-selected').text()).toBe('12:00pm (1 hr)');
            });

            it('should only show options in the timepicker dropdown that has 0 or more duration', function() {
                var $durationDropdown;

                field.def.time = {
                    duration: {
                        relative_to: 'foo'
                    }
                };
                field.name = 'bar';
                field.model.set({
                    foo: '2014-07-17T11:00',
                    bar: '2014-07-17T12:00'
                });

                field.render();
                field.$(field.secondaryFieldTag).focus();

                $durationDropdown = field.$(field.secondaryFieldTag).data().timepickerList;

                expect($durationDropdown.find('li').first().text()).toBe('11:00am (0 mins)');
                expect($durationDropdown.find('li').last().text()).toBe('11:45pm (12 hrs 45 mins)');
            });

            it('should not display duration if duration has not been enabled in the view definition', function() {
                var $durationDropdown;

                field.name = 'bar';
                field.model.set({
                    foo: '2014-07-17T11:00',
                    bar: '2014-07-17T12:00'
                });

                field.render();
                field.$(field.secondaryFieldTag).focus();

                $durationDropdown = field.$(field.secondaryFieldTag).data().timepickerList;

                expect($durationDropdown.find('.ui-timepicker-duration').length).toBe(0);
            });

            it('should not display duration if the datetime is not on the same day as its relative_to field.', function() {
                var $durationDropdown;

                field.name = 'bar';
                field.model.set({
                    foo: '2014-07-17T11:00',
                    bar: '2014-07-18T12:00'
                });

                field.render();
                field.$(field.secondaryFieldTag).focus();

                $durationDropdown = field.$(field.secondaryFieldTag).data().timepickerList;

                expect($durationDropdown.find('.ui-timepicker-duration').length).toBe(0);
            });
        });
    });
});
