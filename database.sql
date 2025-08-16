
USE inventory_db;


CREATE TABLE `logistics_table` (
  `id_logistic` INT NOT NULL AUTO_INCREMENT,
  `items_logistic` VARCHAR(45) NOT NULL,
  `destination` VARCHAR(45) NOT NULL,
  `delivery_time` VARCHAR(45) NOT NULL,
  `pickup_time` VARCHAR(45) NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_logistic`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
//wag kalimutan mag drop ng table, eto yung bagong table natin
CREATE TABLE `inventory_table` (
  `id_inventory` INT NOT NULL AUTO_INCREMENT,
  `warehouse` VARCHAR(45) NOT NULL,
  `itemname_invent` VARCHAR(45) NOT NULL,
  `stock_invent` INT NOT NULL,
  `restock_invent` INT NOT NULL,
  `categories`VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_inventory`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `order_table` (
  `id_order` INT NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(45) NOT NULL,
  `destination_order` VARCHAR(45) NOT NULL,
  `itemname_order` VARCHAR(45) NOT NULL,
  `quantity_order` INT NOT NULL,
  `date_of_order` DATE NOT NULL,
  PRIMARY KEY (`id_order`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- drop table supplier table gawa ulit
CREATE TABLE `supplier_table` (
  `id_supplier` INT NOT NULL AUTO_INCREMENT,
  `name_supplier` VARCHAR(45) NOT NULL,
  `contact_name` VARCHAR(45) NOT NULL,
  `supply` VARCHAR(255) NOT NULL,  -- Comma-separated list of supplied items
  `category` VARCHAR(45) NOT NULL, -- Main category of supplies
  `ratings` DOUBLE NULL,
  `review` TEXT NULL,              -- Supplier review/notes
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

drop table supplier_table;
CREATE TABLE `order_receipt` (
  `id_receipt` INT NOT NULL AUTO_INCREMENT,
  `item_receipt` VARCHAR(45) NOT NULL,
  `quantity_reciept` INT NOT NULL,
  `arrive_receipt` DATE NOT NULL,
  `destination_receipt` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_receipt`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `history_table` (
  `id_history` INT NOT NULL AUTO_INCREMENT,
  `itemname_history` VARCHAR(45) NOT NULL,
  `companyname_history` VARCHAR(45) NOT NULL,
  `arrive_history` DATE NOT NULL,
  `status_history` VARCHAR(45) NOT NULL,
  `quantity_history` INT NOT NULL,
  PRIMARY KEY (`id_history`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO supplier_table (id_supplier, name_supplier, ratings, contact_name, supply, review) VALUES
(1000, 'Supplier 1', 4.5, 'John', 'Supply Name 1', 'Good'),
(1001, 'Supplier 2', 3.8, 'David', 'Supply Name 2', 'You did well'),
(1002, 'Supplier 3', 5.0, 'Mary', 'Supply Name 3', 'Okay'),
(1003, 'Supplier 4', 3.9, 'Dwight', 'Supply Name 4', 'Just fine'),
(1004, 'Supplier 5', 4.2, 'Rheal', 'Supply Name 5', 'Do better');

ALTER TABLE supplier_table
ADD COLUMN contact_name VARCHAR(45) NULL,
ADD COLUMN supply VARCHAR(100) NULL,
ADD COLUMN review VARCHAR(255) NULL;



ALTER TABLE supplier_table ADD category VARCHAR(45) DEFAULT NULL;
  
UPDATE supplier_table SET category = 'Electronics' WHERE id_supplier = 1000;
UPDATE supplier_table SET category = 'Peripherals' WHERE id_supplier = 1001;
UPDATE supplier_table SET category = 'Furniture' WHERE id_supplier = 1002;
UPDATE supplier_table SET category = 'Stationery' WHERE id_supplier = 1003;
UPDATE supplier_table SET category = 'Cleaning Supplies' WHERE id_supplier = 1004;

INSERT INTO order_table (customer_name, destination_order, itemname_order, quantity_order, date_of_order) VALUES
('Inventory', 'Warehouse 4', 'Stapler', 80, '2025-08-05'),
('Inventory', 'Warehouse 2', 'Keyboard', 150, '2025-08-02'),
('Inventory', 'Warehouse 2', 'Mouse', 150, '2025-08-02'),
('Inventory', 'Warehouse 5', 'Mop', 200, '2025-08-03'),
('Inventory', 'Warehouse 4', 'Pen', 50, '2025-08-04'),
('Inventory', 'Warehouse 3', 'Chair', 231, '2025-08-04'),
('Inventory', 'Warehouse 1', 'Desktop', 80, '2025-08-05');


SELECT id_supplier, name_supplier, supply FROM supplier_table;
UPDATE `inventory_db`.`supplier_table` SET `supply` = 'Desktop, Laptop, Monitor, Phone' WHERE (`id_supplier` = '1000');
UPDATE `inventory_db`.`supplier_table` SET `supply` = 'Keyboard, Mouse, Headset' WHERE (`id_supplier` = '1001');
UPDATE `inventory_db`.`supplier_table` SET `supply` = 'Chair, Desk, Cabinet' WHERE (`id_supplier` = '1002');
UPDATE `inventory_db`.`supplier_table` SET `supply` = 'Pen, Notebook, Stapler' WHERE (`id_supplier` = '1003');
UPDATE `inventory_db`.`supplier_table` SET `supply` = 'Mop, Detergent, Brush' WHERE (`id_supplier` = '1004');




CREATE TABLE warehouse_quantities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  warehouse VARCHAR(45) NOT NULL,
  item_name VARCHAR(45) NOT NULL,
  total_quantity INT NOT NULL DEFAULT 0,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY (warehouse, item_name)
);



CREATE TABLE logistics_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(255) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    pickup_date DATE,
    delivery_date DATE,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO logistics_table (id, item, destination, delivery_date, pickup_date, status) VALUES
(1, 'Brief - Black', 'Cebu City', '2025-08-01', '2025-07-30', 'Shipped'),
(2, 'Hoodie - Blue', 'Davao City', '2025-08-03', '2025-08-01', 'In Transit'),
(3, 'Cargo Pants', 'Baguio City', '2025-08-05', '2025-08-02', 'Pending'),
(4, 'Cap - Red', 'Makati City', '2025-08-04', '2025-08-02', 'Shipped');


<<<<<<< HEAD
B4B4B8
C7C8CC
E3E1D9
F2EFE5
=======
drop table inventory_table;


INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 1', 'Rheal', 'Desktop', 'Electronics', '4.5', 'Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 1', 'Rheal', 'Laptop', 'Electronics', '4.5', 'Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 1', 'Rheal', 'Monitor', 'Electronics', '4.5', 'Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 1', 'Rheal', 'Phone', 'Electronics', '4.5', 'Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 2', 'Dwight', 'Keyboard', 'Peripherals', '4.2', 'Okay');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 2', 'Dwight', 'Mouse', 'Peripherals', '4.2', 'Okay');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 2', 'Dwight', 'Headset', 'Peripherals', '4.2', 'Okay');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 3', 'Ralp', 'Chair', 'Furniture', '3.5', 'Decent');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 3', 'Ralp', 'Desk', 'Furniture', '3.5', 'Decent');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 3', 'Ralp', 'Cabinet', 'Furniture', '3.5', 'Decent');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 4', 'Doniel', 'Pen', 'Stationery', '5.0', 'Very Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 4', 'Doniel', 'Notebook', 'Stationery', '5.0', 'Very Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 4', 'Doniel', 'Stapler', 'Stationery', '5.0', 'Very Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 5', 'Lee', 'Mop', 'Cleaning Supplies', '5.0', 'Very Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 5', 'Lee', 'Detergent', 'Cleaning Supplies', '5.0', 'Very Good');
INSERT INTO `inventory_db`.`supplier_table` (`name_supplier`, `contact_name`, `supply`, `category`, `ratings`, `review`) VALUES ('Supplier 5', 'Lee', 'Brush', 'Cleaning Supplies', '5.0', 'Very Good');


ALTER TABLE order_receipt ADD COLUMN supplier_name VARCHAR(45) AFTER warehouse;

CREATE TABLE `order_receipt` (
  `id_receipt` INT NOT NULL AUTO_INCREMENT,
  `item_receipt` VARCHAR(45) NOT NULL,
  `quantity_reciept` INT NOT NULL,
  `arrive_receipt` DATE NOT NULL,
  `destination_receipt` VARCHAR(45) NOT NULL,
  `warehouse` VARCHAR(1324124)not null,
  `supplier_name` VARCHAR(234)not null,
  PRIMARY KEY (`id_receipt`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



select * from inventory_table;
>>>>>>> e445f5fa42f2800aa9b2efb0b83b0d5734003343
