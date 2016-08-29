describe('Base.Fields.Float', function() {

    var app, field;

    beforeEach(function() {
        app = SugarTest.app;
    });

    describe('default field definition', function() {

        beforeEach(function() {

            field = SugarTest.createField('base', 'foo', 'float', 'detail');
        });

        afterEach(function() {
            sinon.collection.restore();
            field.dispose();
            field = null;
        });

        it('should format/unformat the value based on user preferences', function() {

            var preferenceStub = sinon.collection.stub(app.user, 'getPreference'),
                value = 12351616461.2551616;

            preferenceStub.withArgs('number_grouping_separator').returns(',');
            preferenceStub.withArgs('decimal_separator').returns('.');
            field.def.precision = 4;

            expect(field.format(value)).toEqual('12,351,616,461.2552');
            expect(field.unformat('12,351,616,461.2552')).toEqual('12351616461.2552');

            preferenceStub.withArgs('number_grouping_separator').returns('.');
            preferenceStub.withArgs('decimal_separator').returns(',');

            expect(field.format(value)).toEqual('12.351.616.461,2552');
            expect(field.unformat('12.351.616.461,2552')).toEqual('12351616461.2552');

            expect(field.unformat('5.000,65,')).toEqual('5.000,65,');
            expect(field.unformat('5.000,65')).toEqual('5000.6500');

            field.def.precision = 2;

            expect(field.format(value)).toEqual('12.351.616.461,26');
            expect(field.unformat('12.351.616.461,26')).toEqual('12351616461.26');

            // this tests the rounding of what will be stored in the model,
            // we should store what will be in the db into the model so SugarLogic works correctly
            expect(field.format('0.001')).toEqual('0,00');
            expect(field.unformat('0,001')).toEqual('0.00');

        });

        it('should format/unformat zero with/without precision', function() {
            var preferenceStub = sinon.collection.stub(app.user, 'getPreference');

            preferenceStub.withArgs('decimal_separator').returns('.');
            preferenceStub.withArgs('decimal_precision').returns(4);

            expect(field.format(0.00)).toEqual('0');
            expect(parseFloat(field.unformat('0.0000'))).toEqual(0);

            field.def.precision = 8;
            expect(field.format(0.00)).toEqual('0.00000000');        
        });

        it('should not format/unformat a non number string', function() {
            expect(field.format('Asdt')).toEqual('Asdt');
            expect(field.unformat('Asdt')).toEqual('Asdt');
        });
    });

    describe('with disable format', function() {

        beforeEach(function() {

            field = SugarTest.createField('base', 'foo', 'float', 'detail', {
                disable_num_format: true
            });
        });

        afterEach(function() {
            sinon.collection.restore();
            field.dispose();
            field = null;
        });

        it('should format/unformat the value not based on user preferences', function() {

            var preferenceStub = sinon.collection.stub(app.user, 'getPreference'),
                value = '12351616461.2551616';

            preferenceStub.withArgs('number_grouping_separator').returns(',');
            preferenceStub.withArgs('decimal_separator').returns('.');
            preferenceStub.withArgs('decimal_precision').returns(4);

            expect(field.format(value)).toEqual(value);
            expect(field.unformat(value.toString())).toEqual(value);

        });
    });
});
