# symfony-delivery-microservices
A simple implementation of the microservices pattern using the Symfony framework. The aim of this repository was to create an application that enables users to create parcel delivery requests, send them to a courier company, track the parcels, and eventually update their customers with the current delivery status.

This goal has been achieved through three independent applications described below:

## App1: Parcel App
This application handles all database operations, such as creating packages, updating their statuses, and requesting status updates. Every operation performed on the database results in queuing a message indicating the need to call a specific courier API operation. This application uses OpenAPI as an intermediary layer between the user and the database.

## App2: Delivery App
This app intercepts messages indicating the creation of a package and then connects to the FedEx API to generate waybills. The operation concludes by queuing a message containing the data returned by FedEx. Upon interception by the Parcel App, this allows for queuing further messages required to initiate tracking and delivery progress update services.

## App3: Notification App
This application listens for messages related to changes in dispatched packages. Once a package is sent or delivered, the recipient is updated via email with the latest package status.
