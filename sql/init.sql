-- 1. users tablosu
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)   NOT NULL UNIQUE,
  password   VARCHAR(255)  NOT NULL,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. entries tablosu
CREATE TABLE IF NOT EXISTS entries (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT           NOT NULL,
  title      VARCHAR(255)  NOT NULL,
  content    TEXT,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. favorites tablosu
CREATE TABLE IF NOT EXISTS favorites (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT           NOT NULL,
  entry_id   INT           NOT NULL,
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (entry_id) REFERENCES entries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. tags tablosu
CREATE TABLE IF NOT EXISTS tags (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50)         NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. entry_tags pivot tablosu
CREATE TABLE IF NOT EXISTS entry_tags (
  entry_id INT NOT NULL,
  tag_id   INT NOT NULL,
  PRIMARY KEY (entry_id, tag_id),
  FOREIGN KEY (entry_id) REFERENCES entries(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id)   REFERENCES tags(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE entries
  ADD COLUMN entry_date DATE NOT NULL DEFAULT (CURRENT_DATE);

ALTER TABLE users 
  ADD COLUMN photo VARCHAR(255) NULL AFTER created_at;

-- sql/init.sql veya ayrı bir migration dosyası:

ALTER TABLE users 
  ADD COLUMN full_name VARCHAR(100) NOT NULL DEFAULT '',
  ADD COLUMN email     VARCHAR(100) NOT NULL DEFAULT '',
  ADD COLUMN avatar    VARCHAR(255)       DEFAULT NULL;
