<?php
    /**
     * Created by PhpStorm.
     * User: th
     * Date: 17 Nov 2016
     * Time: 10:03
     */

    namespace Eltrino\PrintOrder\Setup;

    use Magento\Framework\DB\Adapter\AdapterInterface;
    use Magento\Framework\DB\Ddl\Table;
    use Magento\Framework\Setup\InstallSchemaInterface;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;

    class InstallSchema implements InstallSchemaInterface {

        public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {

            $installer = $setup;
            $installer->startSetup();
            if( !$installer->tableExists( 'eltrino_guests_orders' ) ) {
                $tableName = $installer->getTable( 'eltrino_guests_orders' );
                $table     = $installer->getConnection()->newTable( $tableName );
                $table->addColumn( 'guests_order_id', Table::TYPE_INTEGER, 10, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ] )->addColumn( 'hash', Table::TYPE_TEXT, 255, [
                    'nullable' => false
                ] )->addColumn( 'order_id', Table::TYPE_TEXT, 255, [
                    'nullable' => false,
                ] )->addColumn( 'expired_at', Table::TYPE_DATETIME, 255, [
                    'nullable' => false,                
                ] );

                $table->addIndex(
                    $installer->getIdxName( $tableName, 'guests_order_id', AdapterInterface::INDEX_TYPE_UNIQUE ),
                    'guests_order_id',
                    [ 'type' => AdapterInterface::INDEX_TYPE_UNIQUE ]
                );

                $table->setComment( 'Eltrino Print Order Guest Order Table' );
                $installer->getConnection()->createTable( $table );

            }


            $installer->endSetup();
        }
    }