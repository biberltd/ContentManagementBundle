<?php
/**
 * sys.tr.php
 *
 * Bu dosya ilgili paketin sistem (hata ve başarı) mesajlarını Türkçe olarak barındırır.
 *
 * @vendor      BiberLtd
 * @package		Core\Bundles\MemberManagementBundle
 * @subpackage	Resources
 * @name	    sys.tr.php
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        06.08.2013
 *
 * =============================================================================================================
 * !!! ÖNEMLİ !!!
 *
 * Çalıştığınız sunucu ortamına göre Symfony ön belleğini temizlemek için işbu dosyayı her değiştirişinizden sonra
 * aşağıdaki komutu çalıştırmalısınız veya app/cache klasörünü silmelisiniz. Aksi takdir de tercümelerde
 * yapmış olduğunuz değişiklikler işleme alıalınmayacaktır.
 *
 * $ sudo -u apache php app/console cache:clear
 * VEYA
 * $ php app/console cache:clear
 * =============================================================================================================
 * TODOs:
 * Yok
 */
/** Nested keys are accepted */
return array(
    /** Member Management Model */
    'err'       => array(
        /** Multi Language Support Model */
        'cmm'   => array(
            'invalid'       =>  array(
                'entity'        => array(
                    'page'      => '"$collection" parametresinde Page objesi bulunmalıdır.',
                ),
                'parameter'     =>  array(
                    'by'        => '"$by" parametresi "entity", "id" veya "code" değerlerinden birini almalıdır.',
                    'collection'=> '"$collection" parametresi bir Array / dizi değeri olmalıdır.',
                    'sortorder' => '"$sortorder" parametresi key => value eşleşmesinden oluşan bir Array / dizi değeri tutmalıdır.',
                ),
            ),
            'not_found'         => 'Aradığınız kriterlere uygun veri bulunamadı.',
            'required'      => array(
                'parameter'     => array(
                    'key'       => '"bypass" parametresi "true" değerine eşitlenmediği müddetçe etkinleştirme anahtarı girmeden kullanıcı hesabını etkinleştiremezsiniz.',
                ),
            ),
            'unknown'                   => 'Bilinmeyen bir hata oluştu, lütfen doğrı olarak MemberManagementModel objesinin yaratılabildiğinden emin olun..',
        ),
    ),
    /** Başarı mesajları */
    'scc'       => array(
        /** Member Management Model */
        'cmm'   => array(
            'default'       => 'Veriler başarıyla işlendi.',
            'deleted'       => 'Veri(ler) başarıyla veri tabanından silindi.',
            'inserted'      => array(
                'group_to_members' => 'Gruplar üye ile ilişkilendirildi.',
                'multiple'      => 'Veriler başarıyla veri tabanına eklendi.',
                'single'        => 'Veri başarıyla veri tabanına eklendi.',
                'member_to_groups' => 'Üyeler ilgili gruplara eklendi.',
            ),
            'updated'       => array(
                'multiple'      => 'Veriler başarıyla güncellendi.',
                'single'        => 'Veri başarıyla güncellendi.',
            ),
            'validated'     => 'Üye girişi onaylandı..',1
        ),
    ),
);
/**
 * Change Log / Değişiklik Kaydı
 * **************************************
 * v1.0.0                      Can Berkol
 * 06.08.2013
 * **************************************
 * A err
 * A err.mlsm
 * A err.mlsm.duplicate
 * A err.mlsm.duplicate.language
 * A err.mlsm.invalid
 * A err.mlsm.invalid.entry
 * A err.mlsm.invalid.entry.language
 * A err.mlsm.invalid.parameter
 * A err.mlsm.invalid.parameter.by
 * A err.mlsm.invalid.parameter.languages
 * A err.mlsm.unknown
 * A scc
 * A scc.smm
 * A scc.smm.default
 * A scc.smm.deleted
 * A scc.smm.inserted
 * A scc.smm.inserted.multiple
 * A scc.smm.inserted.single
 * A scc.smm.updated
 * A scc.smm.updated.multiple
 * A scc.smm.updated.single
 */