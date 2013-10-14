<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Installation/maj des tables cv
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function cv_upgrade($nom_meta_base_version, $version_cible){
	$maj = array();
	
	// Première installation
	$maj['create'] = array(
		array('cv_configuration_base',true),
		array('cv_creer_rubriques',true)
	);
	
	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

function cv_configuration_base(){
	/**
	 * Désactivation de fontionnalités du privé:
	 * -* la messagerie
	 * -* les forums
	 */
	ecrire_meta("messagerie_agenda", "non");
	ecrire_meta("forum_prive_admin","non");
	ecrire_meta("forum_prive_objets","non");
	ecrire_meta("forum_prive","non");
	
	/**
	 * Ne pas activer les inscriptions de rédacteurs
	 */
	ecrire_meta("accepter_inscriptions","non");
	
	/**
	 * Activation des documents sur les articles
	 */
	ecrire_meta("documents_objets", implode(',',array('spip_articles')));
}

function cv_creer_rubriques(){
	include_spip('action/editer_rubrique');
	if(!sql_getfetsel('id_rubrique','spip_rubriques','composition="experience"')){
		$rub_experience = insert_rubrique(0);
		revisions_rubriques($rub_experience, array('titre' =>'1. '._T('cv:titre_rubrique_experience'),'composition'=>'experience','agenda'=>1));
	}
	if(!sql_getfetsel('id_rubrique','spip_rubriques','composition="formation"')){
		$rub_formation = insert_rubrique(0);
		revisions_rubriques($rub_formation, array('titre' =>'2. '._T('cv:titre_rubrique_formation'),'composition'=>'formation','agenda'=>1));
	}
	if(!sql_getfetsel('id_rubrique','spip_rubriques','composition="projets"')){
		$rub_projets = insert_rubrique(0);
		revisions_rubriques($rub_projets, array('titre' =>'3. '._T('cv:titre_rubrique_projets'),'composition'=>'projets','agenda'=>1));
	}
	if(!sql_getfetsel('id_rubrique','spip_rubriques','composition="portfolio"')){
		$rub_portfolio = insert_rubrique(0);
		revisions_rubriques($rub_portfolio, array('titre' =>'4. '._T('cv:titre_rubrique_portfolio'),'composition'=>'portfolio','agenda'=>1));
	}
}
/**
 * Desinstallation/suppression des tables cv
 *
 * @param string $nom_meta_base_version
 */
function cv_vider_tables($nom_meta_base_version) {
	effacer_meta($nom_meta_base_version);

	// Effacer la config
	effacer_meta('cv');
}

?>
