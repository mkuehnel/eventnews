<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Add new controller/action
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems']['News->month']
    = 'Month view';

/***********
 * Hooks
 */

// Hide not needed fields in FormEngine
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\FormEngineHook::class;

// Update flexforms
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Hooks/BackendUtility.php']['updateFlexforms']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\BackendUtility::class . '->update';

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news'][\GeorgRinger\News\Hooks\PageLayoutView::class]['extensionSummary']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\PageLayoutView::class . '->extensionSummary';

// Extend the query
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded']['eventnews']
    = \GeorgRinger\Eventnews\Hooks\AbstractDemandedRepository::class . '->modify';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\Eventnews\Backend\FormDataProvider\EventNewsRowInitializeNew::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
    ]
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['eventnews'] =
    \GeorgRinger\Eventnews\Hooks\Backend\EventNewsDataHandlerHook::class;

/***********
 * Extend EXT:news
 */

$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'eventnews';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Controller/NewsController'][] = 'eventnews';

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class)->connect(
    \GeorgRinger\News\Domain\Service\NewsImportService::class,
    'postHydrate',
    \GeorgRinger\Eventnews\Aspect\NewsImportAspect::class,
    'postHydrate'
);

$emConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['eventnews']);
// override language files of news
if (is_array($emConfiguration) && (bool)$emConfiguration['overrideAdministrationModuleLabel']) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['default']['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][] = 'EXT:eventnews/Resources/Private/Language/Overrides/locallang_modadministration.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:news/Resources/Private/Language/locallang_modadministration.xlf'][] = 'EXT:eventnews/Resources/Private/Language/Overrides/de.locallang_modadministration.xlf';
}
