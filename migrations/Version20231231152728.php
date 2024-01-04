<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231231152728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrato (id INT AUTO_INCREMENT NOT NULL, numero_contrato VARCHAR(100) NOT NULL, valor DOUBLE PRECISION NOT NULL, fecha DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuotas (id INT AUTO_INCREMENT NOT NULL, contrato_id INT DEFAULT NULL, num_cuotas INT NOT NULL, monto_mes DOUBLE PRECISION NOT NULL, fecha_pago DATE NOT NULL, INDEX IDX_8BC7EE5170AE7BF1 (contrato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuotas_servicio_pago (cuotas_id INT NOT NULL, servicio_pago_id INT NOT NULL, INDEX IDX_F285B563CEC2084D (cuotas_id), INDEX IDX_F285B563DAC8199C (servicio_pago_id), PRIMARY KEY(cuotas_id, servicio_pago_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicio_pago (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, interes DOUBLE PRECISION NOT NULL, comision DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicio_pago_cuotas (servicio_pago_id INT NOT NULL, cuotas_id INT NOT NULL, INDEX IDX_D94CBF9EDAC8199C (servicio_pago_id), INDEX IDX_D94CBF9ECEC2084D (cuotas_id), PRIMARY KEY(servicio_pago_id, cuotas_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cuotas ADD CONSTRAINT FK_8BC7EE5170AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('ALTER TABLE cuotas_servicio_pago ADD CONSTRAINT FK_F285B563CEC2084D FOREIGN KEY (cuotas_id) REFERENCES cuotas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cuotas_servicio_pago ADD CONSTRAINT FK_F285B563DAC8199C FOREIGN KEY (servicio_pago_id) REFERENCES servicio_pago (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE servicio_pago_cuotas ADD CONSTRAINT FK_D94CBF9EDAC8199C FOREIGN KEY (servicio_pago_id) REFERENCES servicio_pago (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE servicio_pago_cuotas ADD CONSTRAINT FK_D94CBF9ECEC2084D FOREIGN KEY (cuotas_id) REFERENCES cuotas (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuotas DROP FOREIGN KEY FK_8BC7EE5170AE7BF1');
        $this->addSql('ALTER TABLE cuotas_servicio_pago DROP FOREIGN KEY FK_F285B563CEC2084D');
        $this->addSql('ALTER TABLE cuotas_servicio_pago DROP FOREIGN KEY FK_F285B563DAC8199C');
        $this->addSql('ALTER TABLE servicio_pago_cuotas DROP FOREIGN KEY FK_D94CBF9EDAC8199C');
        $this->addSql('ALTER TABLE servicio_pago_cuotas DROP FOREIGN KEY FK_D94CBF9ECEC2084D');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP TABLE cuotas');
        $this->addSql('DROP TABLE cuotas_servicio_pago');
        $this->addSql('DROP TABLE servicio_pago');
        $this->addSql('DROP TABLE servicio_pago_cuotas');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
