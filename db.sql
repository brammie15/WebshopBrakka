create table category
(
    categoryID int auto_increment
        primary key,
    name       varchar(35) not null
);

create table employee
(
    employeeID    int auto_increment
        primary key,
    passwordHash  varchar(50) not null,
    email         varchar(40) not null,
    phone         varchar(30) not null,
    paymentMethod varchar(50) not null,
    streetName    varchar(40) not null,
    streetNummer  int(10)     not null,
    city          varchar(30) not null
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
    passwordHash varchar(50)  not null,
    email        varchar(500) not null,
    phoneNumber  varchar(100) not null,
    streetName   varchar(200) null,
    streetNumber varchar(100) null,
    cityName     varchar(100) null,
    birthday     date         null,
    postcode     varchar(20)  null
);

create table `order`
(
    orderID       int auto_increment
        primary key,
    hasBeenOrderd tinyint(1) not null,
    orderDate     date       not null,
    totalPrice    double     not null,
    userID        int        not null,
    employeeID    int        null,
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

