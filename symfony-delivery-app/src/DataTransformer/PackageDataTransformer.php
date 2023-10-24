<?php

namespace App\CourierApi\Fedex\Adapter\ShipAPI;

use App\Dto\PackageCreateInput;
use App\Dto\ParcelCreateInput;
use App\Dto\ReceiverCreateInput;
use DateTime;

class PackageDataTransformer {


    /**
     * Creates API request payload for intra-community shippings
     *
     * @param PackageCreateInput $package
     * @param CreateShipmentV1Input $input
     *
     * @return array
     */
    public function transform(PackageCreateInput $package) : array
    {
        $shipDate = new DateTime();
        $shipDate = $shipDate->format('Y-m-d');

        $output = [
            "x-customer-transaction-id" =>  uniqid(),
            "mergeLabelDocOption" =>  "LABELS_AND_DOCS",
            "requestedShipment" =>  [
                "shipDatestamp" =>  $shipDate,
                "totalDeclaredValue" =>  "PLN",
                "shipper" =>  [
                    "address" =>  $this->createAddress($package->receiver),
                    "contact" => $this->createContact($package->getSenderAddress())
                ],
                "recipients" =>  [
                    [
                        "address" =>  $this->createAddress($package->receiver),
                        "contact" => $this->createContact($package->receiver)
                    ]
                ],
                "pickupType" =>  "USE_SCHEDULED_PICKUP",
                "serviceType" =>  "FEDEX_REGIONAL_ECONOMY",
                "packagingType" => "YOUR_PACKAGING",
                "totalWeight" =>  $package->parcel->weight,
                "origin" =>  [
                    "address" =>  $this->createAddress($package->getSenderAddress()),
                    "contact" =>  $this->createContact($package->getSenderAddress())
                ],
                "shippingChargesPayment" =>  [
                    "paymentType" =>  "SENDER",
                    "payor" =>  [
                        "responsibleParty" =>  [
                            "address" =>  $this->createAddress($package->getSenderAddress()),
                            "contact" =>  $this->createContact($package->getSenderAddress()),
                            "accountNumber" =>  [
                                "value" =>  $input->accountNumber
                            ]
                        ]
                    ]
                ],
                "customsClearanceDetail" =>  [
                    "dutiesPayment" =>  [
                        "payor" =>  [
                            "responsibleParty" =>  [
                                "address" =>  $this->createAddress($package->getSenderAddress()),
                                "contact" =>  $this->createContact($package->getSenderAddress()),
                                "accountNumber" =>  [
                                    "value" =>  $input->accountNumber
                                ],
                                "tins" =>  [
                                    [
                                        "number" =>  $input->vatin,
                                        "tinType" =>  "FEDERAL",
                                        "usage" =>  "usage",
                                        "effectiveDate" =>  "2000-01-23T04:56:07.000+00:00",
                                        "expirationDate" =>  "2040-01-23T04:56:07.000+00:00"
                                    ]
                                ]
                            ]
                        ],
                        "paymentType" =>  "SENDER"
                    ],
                    "commodities" =>  [
                        [
                            "unitPrice" =>  $this->getMoneyValue(0, "PLN"),
                            "additionalMeasures" =>  [
                                [
                                    "quantity" =>  $package->parcel->weight,
                                    "units" =>  "KG"
                                ]
                            ],
                            "numberOfPieces" =>  1,
                            "quantity" =>  1,
                            "quantityUnits" =>  "EA",
                            "customsValue" =>  $this->getMoneyValue(0, "PLN"),
                            "countryOfManufacture" =>  "PL",
                            "description" =>  "Parcel description",
                            "name" =>  "Parcel contents",
                            "weight" =>  [
                                "units" =>  "KG",
                                "value" =>  $package->parcel->weight
                            ]
                        ]
                    ],
                    "accountNumber" =>  [
                        "value" =>  $input->accountNumber
                    ],
                    "totalCustomsValue" =>  $this->getMoneyValue(0, "PLN")
                ],
                "labelSpecification" =>  [
                    "labelFormatType" =>  "COMMON2D",
                    "labelStockType" =>  "PAPER_4X6",
                    "labelRotation" =>  "NONE",
                    "imageType" =>  "PDF",
                    "labelPrintingOrientation" =>  "TOP_EDGE_OF_TEXT_FIRST",
                    "returnedDispositionDetail" =>  false
                ],
                "shippingDocumentSpecification" =>  [
                    "shippingDocumentTypes" =>  [
                        "LABEL"
                    ],
                    "certificateOfOrigin" =>  [
                        "customerImageUsages" =>  [
                            [
                                "id" =>  "IMAGE_5",
                                "type" =>  "SIGNATURE",
                                "providedImageType" =>  "SIGNATURE"
                            ]
                        ],
                        "documentFormat" =>  [
                            "provideInstructions" =>  true,
                            "optionsRequested" =>  [
                                "options" =>  [
                                    "SUPPRESS_ADDITIONAL_LANGUAGES",
                                    "SHIPPING_LABEL_LAST"
                                ]
                            ],
                            "stockType" =>  "PAPER_LETTER",
                            "dispositions" =>  [
                                [
                                    "eMailDetail" =>  [
                                        "eMailRecipients" =>  [
                                            [
                                                "emailAddress" =>  $package->receiver->emailAddress,
                                                "recipientType" =>  "THIRD_PARTY"
                                            ]
                                        ],
                                        "locale" =>  "pl_PL",
                                        "grouping" =>  "NONE"
                                    ],
                                    "dispositionType" =>  "CONFIRMED"
                                ]
                            ],
                            "locale" =>  "pl_PL",
                            "docType" =>  "PDF"
                        ]
                    ]
                ],
                "rateRequestType" =>  [
                    "NONE"
                ],
                "preferredCurrency" =>  "PLN",
                "totalPackageCount" =>  1,
                "requestedPackageLineItems" =>  $this->createParcels($package, $input)
            ],
            "labelResponseOptions" =>  "LABEL",
            "accountNumber" =>  [
                "value" =>  $input->accountNumber
            ]
        ];

        return $output;
    }

    /**
     * Creates address payload from sender or recipient entity
     *
     * @var Address $address
     * @return array
     */
    protected function createAddress(ReceiverCreateInput $address) : array
    {
        return [
            "streetLines" =>  [
                $address->address
            ],
            "city" =>  $address->city,
            "postalCode" =>  $address->postCode,
            "countryCode" =>  $address->country
        ];
    }

    /**
     * Creates contact person from sender or recipient entity
     *
     * @var Address $address
     * @return array
     */
    protected function createContact(Address $address) : array
    {
        return [
            "phoneNumber" =>  $address->getPhoneNumber() ?? "0",
            "companyName" =>  $address->getCompanyName()
        ];
    }

    /**
     * Returns total weight of the shipping in kg
     *
     * @param Shipping $shipping
     * @return float
     */
    protected function getTotalWeight(Shipping $shipping) : float
    {
        return intval($shipping->getParameterByName(ShippingParameters::SHIPPING_WEIGHT_TOTAL)) / 1000;
    }

    /**
     * Creates money value payload
     *
     * @param float $amount
     * @param string $currency|Currencies::DEFAULT_CURRENCY
     * @return array
     */
    protected function getMoneyValue(float $amount, $currency = Currencies::DEFAULT_CURRENCY) : array
    {
        return [
            "amount" =>  $amount,
            "currency" =>  $currency
        ];
    }

    /**
     * Creates parcels payload from shipping entity
     */
    protected function createParcels(Shipping $shipping, CreateShipmentV1Input $input) : array
    {
        $output = [];
        foreach ($shipping->getParcels() as $parcel) {
            $parcel = [
                "customerReferences" =>  [],
                "weight" =>  [
                    "units" =>  FedexUnit::FEDEX_WEIGHT_UNIT_KG,
                    "value" =>  $parcel->getWeight() / 1000
                ],
                "dimensions" =>  [
                    "length" =>  $parcel->getLength() / 10,
                    "width" =>  $parcel->getWidth() / 10,
                    "height" =>  $parcel->getHeight() / 10,
                    "units" =>  FedexUnit::FEDEX_LENGTH_UNIT_CM
                ],
                "itemDescription" =>  $parcel->getParameterByName(ShippingParameters::SHIPPING_PARCEL_CONTENTS)
            ];

            $references = [];
            $references[] = $input->referenceNumber1 ?? $shipping->getParameterByName(ShippingParameters::SHIPPING_REFERENCE_NUMBER1);
            $references[] = $input->referenceNumber2 ?? $shipping->getParameterByName(ShippingParameters::SHIPPING_REFERENCE_NUMBER2);

            $references = \array_filter($references);

            if (isset($references[0]) && !empty($references[0])) {
                $parcel['customerReferences'][] = [
                    'customerReferenceType' => 'CUSTOMER_REFERENCE',
                    'value' => $references[0]
                ];
            }

            if (isset($references[1]) && !empty($references[1])) {
                $parcel['customerReferences'][] = [
                    'customerReferenceType' => 'CUSTOMER_REFERENCE',
                    'value' => $references[1]
                ];
            }

            $output[] = $parcel;
        }

        return $output;
    }
}
