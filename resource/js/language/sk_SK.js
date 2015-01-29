(function($) {
    /**
     * Slovak language package
     * Translated by @budik21
     */
    FormValidation.I18n = $.extend(true, FormValidation.I18n, {
        'sk_SK': {
            base64: {
                'default': 'Prosím zadajte správny base64'
            },
            between: {
                'default': 'Prosím zadajte hodnotu medzi %s a %s',
                notInclusive: 'Prosím zadajte hodnotu medzi %s a %s (vrátane týchto čísel)'
            },
            bic: {
                'default': 'Prosím zadajte správné BIC číslo'
            },
            callback: {
                'default': 'Prosím zadajte správnú hodnotu'
            },
            choice: {
                'default': 'Prosím vyberte správnú hodnotu',
                less: 'Hodnota musí byť minimálne %s',
                more: 'Hodnota nesmie byť viac ako %s',
                between: 'Prosím vyberte medzi %s a %s'
            },
            color: {
                'default': 'Prosím zadajte správnú farbu'
            },
            creditCard: {
                'default': 'Prosím zadajte správné číslo kreditnej karty'
            },
            cusip: {
                'default': 'Prosím zadajte správné CUSIP číslo'
            },
            cvv: {
                'default': 'Prosím zadajte správné CVV číslo'
            },
            date: {
                'default': 'Prosím zadajte správné dátum',
                min: 'Prosím zadajte dátum pred %s',
                max: 'Prosím zadajte dátum po %s',
                range: 'Prosím zadajte dátum v rodzmezí %s až %s'
            },
            different: {
                'default': 'Prosím zadajte inú hodnotu'
            },
            digits: {
                'default': 'Toto pole môže obsahovať len čísla'
            },
            ean: {
                'default': 'Prosím zadajte správné EAN číslo'
            },
            ein: {
                'default': 'Prosím zadajte správné EIN číslo'
            },
            emailAddress: {
                'default': 'Prosím zadajte správnú emailovou adresu'
            },
            file: {
                'default': 'Prosím vyberte súbor'
            },
            greaterThan: {
                'default': 'Prosím zadajte hodnotu väčší ako alebo rovnú %s',
                notInclusive: 'Prosím zadajte hodnotu väčší ako %s'
            },
            grid: {
                'default': 'Prosím zadajte správné GRId číslo'
            },
            hex: {
                'default': 'Prosím zadajte správné hexadecimálné číslo'
            },
            iban: {
                'default': 'Prosím zadajte správné IBAN číslo',
                country: 'Prosím zadajte správné IBAN číslo pre %s',
                countries: {
                    AD: 'Andorru',
                    AE: 'Spojené arabské emiráty',
                    AL: 'Albaniu',
                    AO: 'Angolu',
                    AT: 'Rakúsko',
                    AZ: 'Ázerbajdžán',
                    BA: 'Bosnu a Herzegovinu',
                    BE: 'Belgiu',
                    BF: 'Burkina Faso',
                    BG: 'Bulharsko',
                    BH: 'Bahrajn',
                    BI: 'Burundi',
                    BJ: 'Benin',
                    BR: 'Brazíliu',
                    CH: 'Švajčarsko',
                    CI: 'Pobřežie Slonoviny',
                    CM: 'Kamerun',
                    CR: 'Kostariku',
                    CV: 'Cape Verde',
                    CY: 'Cyprus',
                    CZ: 'Českú republiku',
                    DE: 'Nemecko',
                    DK: 'Dánsko',
                    DO: 'Dominikánskú republiku',
                    DZ: 'Alžírsko',
                    EE: 'Estónsko',
                    ES: 'Španielsko',
                    FI: 'Fínsko',
                    FO: 'Faerské ostrovy',
                    FR: 'Francúzsko',
                    GB: 'Veľkú Britániu',
                    GE: 'Gruzínsko',
                    GI: 'Gibraltar',
                    GL: 'Grónsko',
                    GR: 'Grécko',
                    GT: 'Guatemalu',
                    HR: 'Chorvátsko',
                    HU: 'Maďarsko',
                    IE: 'Irsko',
                    IL: 'Israel',
                    IR: 'Irán',
                    IS: 'Island',
                    IT: 'Taliansko',
                    JO: 'Jordánsko',
                    KW: 'Kuwait',
                    KZ: 'Kazachstan',
                    LB: 'Libanon',
                    LI: 'Lichtenštajnsko',
                    LT: 'Litvu',
                    LU: 'Lucembursko',
                    LV: 'Lotyšsko',
                    MC: 'Monako',
                    MD: 'Moldavsko',
                    ME: 'Čiernú horu',
                    MG: 'Madagaskar',
                    MK: 'Makedoniu',
                    ML: 'Mali',
                    MR: 'Mauritániu',
                    MT: 'Maltu',
                    MU: 'Mauritius',
                    MZ: 'Mosambik',
                    NL: 'Holandsko',
                    NO: 'Norsko',
                    PK: 'Pakistán',
                    PL: 'Polsko',
                    PS: 'Palestinu',
                    PT: 'Portugalsko',
                    QA: 'Katar',
                    RO: 'Rumunsko',
                    RS: 'Srbsko',
                    SA: 'Saudskú Arábiu',
                    SE: 'Švédsko',
                    SI: 'Slovinsko',
                    SK: 'Slovensko',
                    SM: 'San Marino',
                    SN: 'Senegal',
                    TN: 'Tunisko',
                    TR: 'Turecko',
                    VG: 'Britské Panenské ostrovy'
                }
            },
            id: {
                'default': 'Prosím zadajte správné rodné číslo',
                country: 'Prosím zadajte správné rodné číslo pre %s',
                countries: {
                    BA: 'Bosnu a Hercegovinu',
                    BG: 'Bulharsko',
                    BR: 'Brazíliu',
                    CH: 'Švajčarsko',
                    CL: 'Chile',
                    CN: 'Čínu',
                    CZ: 'Českú Republiku',
                    DK: 'Dánsko',
                    EE: 'Estónsko',
                    ES: 'Španielsko',
                    FI: 'Fínsko',
                    HR: 'Chorvátsko',
                    IE: 'Irsko',
                    IS: 'Island',
                    LT: 'Litvu',
                    LV: 'Lotyšsko',
                    ME: 'Čiernú horu',
                    MK: 'Makedoniu',
                    NL: 'Holandsko',
                    RO: 'Rumunsko',
                    RS: 'Srbsko',
                    SE: 'Švédsko',
                    SI: 'Slovinsko',
                    SK: 'Slovensko',
                    SM: 'San Marino',
                    TH: 'Thajsko',
                    ZA: 'Južnú Afriku'
                }
            },
            identical: {
                'default': 'Prosím zadajte rovnakú hodnotu'
            },
            imei: {
                'default': 'Prosím zadajte správné IMEI číslo'
            },
            imo: {
                'default': 'Prosím zadajte správné IMO číslo'
            },
            integer: {
                'default': 'Prosím zadajte celé číslo'
            },
            ip: {
                'default': 'Prosím zadajte správnú IP adresu',
                ipv4: 'Prosím zadajte správnú IPv4 adresu',
                ipv6: 'Prosím zadajte správnú IPv6 adresu'
            },
            isbn: {
                'default': 'Prosím zadajte správné ISBN číslo'
            },
            isin: {
                'default': 'Prosím zadajte správné ISIN číslo'
            },
            ismn: {
                'default': 'Prosím zadajte správné ISMN číslo'
            },
            issn: {
                'default': 'Prosím zadajte správné ISSN číslo'
            },
            lessThan: {
                'default': 'Prosím zadajte hodnotu menšiu alebo rovnú %s',
                notInclusive: 'Prosím zadajte hodnotu menšiu ako %s'
            },
            mac: {
                'default': 'Prosím zadajte správnú MAC adresu'
            },
            meid: {
                'default': 'Prosím zadajte správné MEID číslo'
            },
            notEmpty: {
                'default': 'Toto pole nesmie byť prázdne'
            },
            numeric: {
                'default': 'Prosím zadajte číselnú hodnotu'
            },
            phone: {
                'default': 'Prosím zadajte správné telefónne číslo',
                country: 'Prosím zadajte správné telefónne číslo pre %s',
                countries: {
                    AE: 'Spojené arabské emiráty',
                    BR: 'Brazíliu',
                    CN: 'Čínu',
                    CZ: 'Českú Republiku',
                    DE: 'Nemecko',
                    DK: 'Dánsko',
                    ES: 'Španielsko',
                    FR: 'Francúzsko',
                    GB: 'Veľkú Britániu',
                    IN: 'India',
                    MA: 'Maroko',
                    PK: 'Pákistán',
                    RO: 'Rumunsko',
                    RU: 'Rusko',
                    SK: 'Slovensko',
                    TH: 'Thajsko',
                    US: 'Spojené Štáty Americké',
                    VE: 'Venezuelu'
                }
            },
            regexp: {
                'default': 'Prosím zadajte hodnotu spĺňajúcu zadanie'
            },
            remote: {
                'default': 'Prosím zadajte správnú hodnotu'
            },
            rtn: {
                'default': 'Prosím zadajte správné RTN číslo'
            },
            sedol: {
                'default': 'Prosím zadajte správné SEDOL číslo'
            },
            siren: {
                'default': 'Prosím zadajte správné SIREN číslo'
            },
            siret: {
                'default': 'Prosím zadajte správné SIRET číslo'
            },
            step: {
                'default': 'Prosím zadajte správny krok %s'
            },
            stringCase: {
                'default': 'Len malá písmena sú povolená v tomto poli',
                upper: 'Len velká písmena sú povolená v tomto poli'
            },
            stringLength: {
                'default': 'Toto pole nesmie byť prázdne',
                less: 'Prosím zadajte hodnotu kratší ako %s znakov',
                more: 'Prosím zadajte hodnotu dlhú %s znakov a viacej',
                between: 'Prosím zadajte hodnotu medzi %s a %s znaky'
            },
            uri: {
                'default': 'Prosím zadajte správnú URI'
            },
            uuid: {
                'default': 'Prosím zadajte správné UUID číslo',
                version: 'Prosím zadajte správné UUID verze %s'
            },
            vat: {
                'default': 'Prosím zadajte správné VAT číslo',
                country: 'Prosím zadajte správné VAT číslo pre %s',
                countries: {
                    AT: 'Rakúsko',
                    BE: 'Belgiu',
                    BG: 'Bulharsko',
                    BR: 'Brazíliu',
                    CH: 'Švajčarsko',
                    CY: 'Cyprus',
                    CZ: 'Českú Republiku',
                    DE: 'Nemecko',
                    DK: 'Dánsko',
                    EE: 'Estónsko',
                    ES: 'Španielsko',
                    FI: 'Fínsko',
                    FR: 'Francúzsko',
                    GB: 'Veľkú Britániu',
                    GR: 'Grécko',
                    EL: 'Grécko',
                    HU: 'Maďarsko',
                    HR: 'Chorvátsko',
                    IE: 'Irsko',
                    IS: 'Island',
                    IT: 'Itálie',
                    LT: 'Litvu',
                    LU: 'Lucembursko',
                    LV: 'Lotyšsko',
                    MT: 'Maltu',
                    NL: 'Holandsko',
                    NO: 'Norsko',
                    PL: 'Polsko',
                    PT: 'Portugalsko',
                    RO: 'Rumunsko',
                    RU: 'Rusko',
                    RS: 'Srbsko',
                    SE: 'Švédsko',
                    SI: 'Slovinsko',
                    SK: 'Slovensko',
                    VE: 'Venezuelu',
                    ZA: 'Južnú Afriku'
                }
            },
            vin: {
                'default': 'Prosím zadajte správné VIN číslo'
            },
            zipCode: {
                'default': 'Prosím zadajte správné PSČ',
                country: 'Prosím zadajte správné PSČ pre %s',
                countries: {
                    AT: 'Rakúsko',
                    BR: 'Brazíliu',
                    CA: 'Kanadu',
                    CH: 'Švajčarsko',
                    CZ: 'Českú Republiku',
                    DE: 'Nemecko',
                    DK: 'Dánsko',
                    ES: 'Španielsko',
                    FR: 'Francúzsko',
                    GB: 'Veľkú Britániu',
                    IE: 'Irsko',
                    IN: 'India',
                    IT: 'Itálie',
                    MA: 'Maroko',
                    NL: 'Holandsko',
                    PT: 'Portugalsko',
                    RO: 'Rumunsko',
                    RU: 'Rusko',
                    SE: 'Švédsko',
                    SG: 'Singapur',
                    SK: 'Slovensko',
                    US: 'Spojené Štáty Americké'
                }
            }
        }
    });
}(window.jQuery));
