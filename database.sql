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

CREATE TABLE `inventory_table` (
  `id_inventory` INT NOT NULL AUTO_INCREMENT,
  `warehouse` VARCHAR(45) NOT NULL,
  `itemname_invent` VARCHAR(45) NOT NULL,
  `stock_invent` INT NOT NULL,
  `restock_invent` INT NOT NULL,
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

CREATE TABLE `supplier_table` (
  `id_supplier` INT NOT NULL AUTO_INCREMENT,
  `name_supplier` VARCHAR(45) NOT NULL,
  `ratings` DOUBLE NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

UPDATE supplier_table SET contact_name = 'John', supply = 'Supply Name 1', review = 'Good' WHERE id_supplier = 1000;
UPDATE supplier_table SET contact_name = 'David', supply = 'Supply Name 2', review = 'You did well' WHERE id_supplier = 1001;
UPDATE supplier_table SET contact_name = 'Mary', supply = 'Supply Name 3', review = 'Okay' WHERE id_supplier = 1002;
UPDATE supplier_table SET contact_name = 'Dwight', supply = 'Supply Name 4', review = 'Just fine' WHERE id_supplier = 1003;
UPDATE supplier_table SET contact_name = 'Rheal', supply = 'Supply Name 5', review = 'Do better' WHERE id_supplier = 1004;


  
