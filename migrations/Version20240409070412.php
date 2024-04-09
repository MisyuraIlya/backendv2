<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409070412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent_objective (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, client_id INT DEFAULT NULL, is_completed TINYINT(1) NOT NULL, completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, week1 TINYINT(1) NOT NULL, week2 TINYINT(1) NOT NULL, week3 TINYINT(1) NOT NULL, week4 TINYINT(1) NOT NULL, hour_from VARCHAR(255) DEFAULT NULL, hour_to VARCHAR(255) DEFAULT NULL, choosed_day VARCHAR(255) DEFAULT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', objective_type VARCHAR(255) NOT NULL, INDEX IDX_4EC277453414710B (agent_id), INDEX IDX_4EC2774519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_target (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, current_value INT NOT NULL, target_value INT NOT NULL, is_completed TINYINT(1) NOT NULL, INDEX IDX_188878DA3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_main (id INT AUTO_INCREMENT NOT NULL, ext_id VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, is_published TINYINT(1) NOT NULL, orden INT DEFAULT NULL, is_in_product_card TINYINT(1) NOT NULL, is_in_filter TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_sub (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_6E081763B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, media_object_id INT DEFAULT NULL, ext_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_published TINYINT(1) NOT NULL, orden INT DEFAULT NULL, lvl_number INT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), INDEX IDX_64C19C164DE5A5 (media_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, agent_approved_id INT DEFAULT NULL, order_ext_id VARCHAR(255) DEFAULT NULL, delivery_date DATE DEFAULT NULL, discount INT DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, order_comment VARCHAR(255) DEFAULT NULL, order_status VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_price INT DEFAULT NULL, document_type VARCHAR(255) NOT NULL, is_buy_by_credit_card TINYINT(1) NOT NULL, is_send_erp TINYINT(1) NOT NULL, send_erp_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_27BA704BA76ED395 (user_id), INDEX IDX_27BA704B3414710B (agent_id), INDEX IDX_27BA704B5E9F256F (agent_approved_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history_detailed (id INT AUTO_INCREMENT NOT NULL, history_id INT DEFAULT NULL, product_id INT DEFAULT NULL, single_price INT DEFAULT NULL, quantity INT DEFAULT NULL, discount INT DEFAULT NULL, total INT DEFAULT NULL, INDEX IDX_C9A1DF641E058452 (history_id), INDEX IDX_C9A1DF644584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', source VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE migvan (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, sku_id INT DEFAULT NULL, is_published TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B1EB65FAA76ED395 (user_id), INDEX IDX_B1EB65FA1777D41C (sku_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_send TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, is_published TINYINT(1) NOT NULL, INDEX IDX_BF5476CA3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, notification_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_35AF9D73A76ED395 (user_id), INDEX IDX_35AF9D73EF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack_main (id INT AUTO_INCREMENT NOT NULL, ext_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, quantity INT DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_list (id INT AUTO_INCREMENT NOT NULL, ext_id VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, discount INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_list_detailed (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, price_list_id INT DEFAULT NULL, price INT NOT NULL, discount INT DEFAULT NULL, INDEX IDX_94C53FFB4584665A (product_id), INDEX IDX_94C53FFB5688DED7 (price_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_list_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, price_list_id INT DEFAULT NULL, expired_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4BEC3A5CA76ED395 (user_id), INDEX IDX_4BEC3A5C5688DED7 (price_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_lvl1_id INT DEFAULT NULL, category_lvl2_id INT DEFAULT NULL, category_lvl3_id INT DEFAULT NULL, sku VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, default_image_path VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, is_published TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', base_price INT DEFAULT NULL, final_price INT DEFAULT NULL, stock INT NOT NULL, pack_quantity INT DEFAULT NULL, discount INT DEFAULT NULL, orden INT DEFAULT NULL, INDEX IDX_D34A04AD468C4EE7 (category_lvl1_id), INDEX IDX_D34A04AD5439E109 (category_lvl2_id), INDEX IDX_D34A04ADEC85866C (category_lvl3_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, attribute_sub_id INT DEFAULT NULL, INDEX IDX_94DA59764584665A (product_id), INDEX IDX_94DA59762788BC83 (attribute_sub_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_images (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, media_object_id INT DEFAULT NULL, INDEX IDX_8263FFCE4584665A (product_id), INDEX IDX_8263FFCE64DE5A5 (media_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_pack (id INT AUTO_INCREMENT NOT NULL, pack_id INT DEFAULT NULL, product_id INT DEFAULT NULL, INDEX IDX_1A367C821919B217 (pack_id), INDEX IDX_1A367C824584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_C74F2195C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, is_registered TINYINT(1) NOT NULL, name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, ext_id VARCHAR(255) DEFAULT NULL, is_blocked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', recovery VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, password_unencrypted VARCHAR(255) DEFAULT NULL, is_allow_order TINYINT(1) DEFAULT 0 NOT NULL, is_allow_all_clients TINYINT(1) DEFAULT 0 NOT NULL, pay_code VARCHAR(255) DEFAULT NULL, pay_des VARCHAR(255) DEFAULT NULL, max_credit DOUBLE PRECISION DEFAULT NULL, max_obligo DOUBLE PRECISION DEFAULT NULL, hp VARCHAR(255) DEFAULT NULL, tax_code VARCHAR(255) DEFAULT NULL, is_agent TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649727ACA70 (parent_id), INDEX IDX_8D93D6493414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent_objective ADD CONSTRAINT FK_4EC277453414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agent_objective ADD CONSTRAINT FK_4EC2774519EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agent_target ADD CONSTRAINT FK_188878DA3414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE attribute_sub ADD CONSTRAINT FK_6E081763B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_main (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C164DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B3414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B5E9F256F FOREIGN KEY (agent_approved_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history_detailed ADD CONSTRAINT FK_C9A1DF641E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE history_detailed ADD CONSTRAINT FK_C9A1DF644584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE migvan ADD CONSTRAINT FK_B1EB65FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE migvan ADD CONSTRAINT FK_B1EB65FA1777D41C FOREIGN KEY (sku_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('ALTER TABLE price_list_detailed ADD CONSTRAINT FK_94C53FFB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE price_list_detailed ADD CONSTRAINT FK_94C53FFB5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('ALTER TABLE price_list_user ADD CONSTRAINT FK_4BEC3A5CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE price_list_user ADD CONSTRAINT FK_4BEC3A5C5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD468C4EE7 FOREIGN KEY (category_lvl1_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD5439E109 FOREIGN KEY (category_lvl2_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEC85866C FOREIGN KEY (category_lvl3_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59764584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA59762788BC83 FOREIGN KEY (attribute_sub_id) REFERENCES attribute_sub (id)');
        $this->addSql('ALTER TABLE product_images ADD CONSTRAINT FK_8263FFCE4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_images ADD CONSTRAINT FK_8263FFCE64DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE product_pack ADD CONSTRAINT FK_1A367C821919B217 FOREIGN KEY (pack_id) REFERENCES pack_main (id)');
        $this->addSql('ALTER TABLE product_pack ADD CONSTRAINT FK_1A367C824584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent_objective DROP FOREIGN KEY FK_4EC277453414710B');
        $this->addSql('ALTER TABLE agent_objective DROP FOREIGN KEY FK_4EC2774519EB6921');
        $this->addSql('ALTER TABLE agent_target DROP FOREIGN KEY FK_188878DA3414710B');
        $this->addSql('ALTER TABLE attribute_sub DROP FOREIGN KEY FK_6E081763B6E62EFA');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C164DE5A5');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BA76ED395');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B3414710B');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B5E9F256F');
        $this->addSql('ALTER TABLE history_detailed DROP FOREIGN KEY FK_C9A1DF641E058452');
        $this->addSql('ALTER TABLE history_detailed DROP FOREIGN KEY FK_C9A1DF644584665A');
        $this->addSql('ALTER TABLE migvan DROP FOREIGN KEY FK_B1EB65FAA76ED395');
        $this->addSql('ALTER TABLE migvan DROP FOREIGN KEY FK_B1EB65FA1777D41C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA3DA5256D');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE price_list_detailed DROP FOREIGN KEY FK_94C53FFB4584665A');
        $this->addSql('ALTER TABLE price_list_detailed DROP FOREIGN KEY FK_94C53FFB5688DED7');
        $this->addSql('ALTER TABLE price_list_user DROP FOREIGN KEY FK_4BEC3A5CA76ED395');
        $this->addSql('ALTER TABLE price_list_user DROP FOREIGN KEY FK_4BEC3A5C5688DED7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD468C4EE7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD5439E109');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEC85866C');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59764584665A');
        $this->addSql('ALTER TABLE product_attribute DROP FOREIGN KEY FK_94DA59762788BC83');
        $this->addSql('ALTER TABLE product_images DROP FOREIGN KEY FK_8263FFCE4584665A');
        $this->addSql('ALTER TABLE product_images DROP FOREIGN KEY FK_8263FFCE64DE5A5');
        $this->addSql('ALTER TABLE product_pack DROP FOREIGN KEY FK_1A367C821919B217');
        $this->addSql('ALTER TABLE product_pack DROP FOREIGN KEY FK_1A367C824584665A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649727ACA70');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493414710B');
        $this->addSql('DROP TABLE agent_objective');
        $this->addSql('DROP TABLE agent_target');
        $this->addSql('DROP TABLE attribute_main');
        $this->addSql('DROP TABLE attribute_sub');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE history_detailed');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE migvan');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP TABLE pack_main');
        $this->addSql('DROP TABLE price_list');
        $this->addSql('DROP TABLE price_list_detailed');
        $this->addSql('DROP TABLE price_list_user');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_attribute');
        $this->addSql('DROP TABLE product_images');
        $this->addSql('DROP TABLE product_pack');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE user');
    }
}
