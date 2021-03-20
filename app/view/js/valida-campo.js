//necessita do plugin jquery.mask
var ValidaCampos = {
    Moeda: function (element) {
        $(element).mask('000000000,00', { reverse: true });
    },

    MoedaUnitarioQuantidade: function (element, n) {
        if (n == "2" || n == "0"){
            $(element).mask('000.000.000,00', { reverse: true });
            // if (!$(element).val().find("R$ ")) {
            //     $(element).val("R$ " + $(element).val());
            // }
        }
        else if (n == "3")
            $(element).mask('000000000,000', { reverse: true });
        else
            $(element).mask('000000000,0000', { reverse: true });
    },

    Nat: function (element) {
        $(element).mask('0.000-0', { reverse: true });
    },
    Placa: function (element) {
        $(element).mask('AAA-AAAA', { reverse: false });
    },
    NCM: function (element) {
        $(element).mask('AAAA.AA.AA', {
            'translation': {
                A: { pattern: /[0-9]/ }
            }
        });
    },
    ValidaData: function (element) {
        $(element).mask("00/00/0000", { placeholder: "__/__/____" });
    },
    CFOP: function (element) {
        $(element).mask('0.000-B', {
            'translation': {
                A: { pattern: /[0-9]/ },
                B: { pattern: /[A-Za-z0-9]/ }
            }
        });
    },
    CRC: function (element) {
        $(element).mask('AA.000.000.A/0', {
            'translation': {
                A: { pattern: /[A-Za-z]/ }
            }
        });
    },
    LimiteNumero: function (element, n) {
        var m = "";
        for (var i = 0; i < n; i++) {
            m += "0";
        }
        $(element).mask(m);
    },
    //quando se passar true como parametro caracteres especias
    Limite: function (element, n, ex) {
        var m = "";
        for (var i = 0; i < n; i++) {
            m += "A";
        }
        if (!ex) {
            $(element).mask(m, {
                'translation': {
                    A: { pattern: /[A-Za-z0-9]/ }
                }
            });
        } else {
            $(element).mask(m, {
                byPassKeys: [8, 9, 37, 38, 39, 40],
                'translation': {
                    A: { pattern: /[A-Za-z0-9 ,.@çáàâãéêôôóõúíÇÁÃÂÀÉÊÕÔÓÚÍ$#-_&%$¨'!"º]/ }
                }
            });
        }
    },

    Login: function (element, n) {
        var m = "";
        for (var i = 0; i < n; i++) {
            m += "A";
        }

        $(element).mask(m, {
            byPassKeys: [8, 9, 37, 38, 39, 40],
            'translation': {
                A: { pattern: /[A-Za-z0-9._-]/ }
            }
        });
    },
    RG: function (element) {
        $(element).mask("00.000.000-0");
    },
    CPF_CNPJ: function (element) {

        $(element).on("paste", function () {
            $(element).val("");
            $(this).unmask();
        });

        var CPF_CNPJMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009999' : '00.000.000/0000-00';
        },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(CPF_CNPJMaskBehavior.apply({}, arguments), options);
            }
        };

        $(element).mask(CPF_CNPJMaskBehavior, spOptions);
    },


    Telefone: function (element) {
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00)00000-0000' : '(00)0000-00009';
        },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

        $(element).mask(SPMaskBehavior, spOptions);
    },

    UF: function (element) {
        $(element).mask('ZZ', {
            translation: {
                'Z': {
                    pattern: /[A-Za-z]/
                }
            }
        });
    },

    CEP: function (element) {
        $(element).mask('00000-000');
    },

    PIS: function (element) {
        $(element).mask('99,999999', {
            'translation': {
                A: { pattern: /[0-9]/ }
            }
        });
    },

    Data: function (element) {
        $(element).datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            autoclose: true
        });
    },
    DRFormula: function (element) {
        var m = "";
        for (var i = 0; i < 100; i++) {
            m += "A";
        }
        $(element).mask(m, {
            'translation': {
                A: { pattern: /[0-9 Gg/*\-\+]/ }
            }
        });
    },

}