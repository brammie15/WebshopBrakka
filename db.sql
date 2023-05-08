create table category
(
    categoryID int auto_increment comment 'The id of the category'
        primary key,
    name       varchar(35) not null comment 'The name of category'
);

create table employee
(
    employeeID   int auto_increment
        primary key,
    passwordHash varchar(256) not null,
    email        varchar(500) not null,
    phone        varchar(100) not null
);

create table product
(
    productID   int auto_increment
        primary key,
    name        varchar(50)  not null,
    price       double       not null,
    categoryID  int          not null,
    imageUrl    varchar(200) null,
    description longtext     null,
    constraint product_category_categoryID_fk
        foreign key (categoryID) references category (categoryID)
);

create table user
(
    userID       int auto_increment
        primary key,
    passwordHash varchar(256) not null,
    email        varchar(500) not null,
    phoneNumber  varchar(100) not null,
    birthday     date         null
);

create table `order`
(
    orderID         int auto_increment comment 'The id of the order'
        primary key,
    hasBeenOrderd   tinyint(1)   not null comment 'boolean to check if the order has been confirmed',
    orderDate       date         not null comment 'the date the order was placed',
    totalPrice      double       not null comment 'The total price paid of the order',
    userID          int          not null comment 'The id of the user who orderd the order',
    employeeID      int          null comment 'The id of the empoyee who placed the order',
    streetNumber    varchar(32)  not null comment 'The streetnummer of the order',
    streetName      varchar(255) not null comment 'the streetName of the order',
    cityName        varchar(255) null comment 'the city of the order',
    postcode        varchar(32)  null,
    hasBeenDeliverd tinyint(1)   null comment 'A boolean to check if the oder has been deliverd (aka if its done)',
    constraint order_employee_employeeID_fk
        foreign key (employeeID) references employee (employeeID),
    constraint order_user_userID_fk
        foreign key (userID) references user (userID)
);

create index userID
    on `order` (userID);

create table orderproduct
(
    orderproductID int auto_increment
        primary key,
    amount         int not null,
    productID      int not null,
    orderID        int not null,
    constraint orderproduct_order_orderID_fk
        foreign key (orderID) references `order` (orderID),
    constraint orderproduct_product_productID_fk
        foreign key (productID) references product (productID)
);

