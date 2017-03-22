CREATE TABLE catalog_category_entity_tmp LIKE catalog_category_entity;
INSERT INTO catalog_category_entity_tmp SELECT * FROM catalog_category_entity;

UPDATE catalog_category_entity cce
SET children_count =
(
  SELECT count(cce2.entity_id)-1 as children_county
  FROM catalog_category_entity_tmp cce2
  WHERE PATH LIKE CONCAT(cce.path,'%')
);

DROP TABLE catalog_category_entity_tmp;

