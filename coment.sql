# comment field column
ALTER table board 
ADD COLUMN comment_cnt INTERGER UNSIGNED DEFAULT 0 
AFTER hit COMMENT 'comment_qty';

# comment
CREATE TABLE `comment` (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  page_id INTEGER NOT NULL,
  parent_id INTEGER NOT NULL,
  title VARCHAR(255), 
  content TEXT,
  likes INTEGER(11),
  approved TINYINT(1),
  create_at DATETIME NOT NULL,
  PRIMARY KEY(idx)
);

  # DEFAULT '' COMMENT 'writer',