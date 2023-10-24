<?php

namespace App\FedexClient\DataTransformer;

use App\Dto\PackageCreateInput;
use App\Dto\ParcelCreateInput;
use App\Dto\ReceiverCreateInput;
use DateTime;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PackageDataTransformer {


    public function __construct(

        #[Autowire('%fedex_account_number%')]
        private readonly string $accountNumber
    ) {}

    /**
     * Creates API request payload for intra-community shippings
     *
     * @param PackageCreateInput $package
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
                "totalDeclaredValue" => $this->getMoneyValue(50, "PLN"),
                "shipper" =>  [
                    "address" =>  $this->createAddress($package->sender),
                    "contact" => $this->createContact($package->sender)
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
                    "address" =>  $this->createAddress($package->sender),
                    "contact" =>  $this->createContact($package->sender)
                ],
                "shippingChargesPayment" =>  [
                    "paymentType" =>  "SENDER",
                    "payor" =>  [
                        "responsibleParty" =>  [
                            "address" =>  $this->createAddress($package->sender),
                            "contact" =>  $this->createContact($package->sender),
                            "accountNumber" =>  [
                                "value" =>  $this->accountNumber
                            ]
                        ]
                    ]
                ],
                "customsClearanceDetail" =>  [
                    "dutiesPayment" =>  [
                        "payor" =>  [
                            "responsibleParty" =>  [
                                "address" =>  $this->createAddress($package->sender),
                                "contact" =>  $this->createContact($package->sender),
                                "accountNumber" =>  [
                                    "value" =>  $this->accountNumber
                                ],
                                "tins" =>  [
                                    [
                                        "number" =>  "PL9471986123",
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
                            "unitPrice" =>  $this->getMoneyValue(50, "PLN"),
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
                        "value" =>  $this->accountNumber
                    ],
                    "totalCustomsValue" =>  $this->getMoneyValue(50, "PLN")
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
                "requestedPackageLineItems" =>  $this->createParcels($package->parcel)
            ],
            "labelResponseOptions" =>  "LABEL",
            "accountNumber" =>  [
                "value" =>  $this->accountNumber
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
    protected function createContact(ReceiverCreateInput $address) : array
    {
        return [
            "phoneNumber" =>  $address->phoneNumber,
            "companyName" =>  $address->companyName
        ];
    }

    /**
     * Creates money value payload
     *
     * @param float $amount
     * @param string $currency|Currencies::DEFAULT_CURRENCY
     * @return array
     */
    protected function getMoneyValue(float $amount, $currency) : array
    {
        return [
            "amount" =>  $amount,
            "currency" =>  $currency
        ];
    }

    /**
     * Creates parcels payload from shipping entity
     *
     * @param ParcelCreateInput $parcel
     * @return array
     */
    protected function createParcels(ParcelCreateInput $parcel) : array
    {
        $parcel = [
            "customerReferences" =>  [
                [
                    "customerReferenceType" => "CUSTOMER_REFERENCE",
                    "value" => "1"
                ]
            ],
            "weight" =>  [
                "units" =>  "KG",
                "value" =>  $parcel->weight
            ],
            "dimensions" =>  [
                "length" =>  $parcel->length,
                "width" =>  $parcel->width,
                "height" =>  $parcel->height,
                "units" =>  "CM"
            ],
            "itemDescription" =>  "Parcel description",
        ];

        return [$parcel];
    }
}
