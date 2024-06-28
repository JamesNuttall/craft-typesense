<?php
/**
 * Typesense plugin for Craft CMS 4.x
 *
 * @link      https://percipio.london/
 * @copyright Copyright (c) 2022 Percipio Global Ltd.
 * @license   https://percipio.london/license
 */

namespace percipiolondon\typesense\controllers;

use Craft;
use craft\errors\MissingComponentException;
use craft\web\Controller;

use percipiolondon\typesense\Typesense;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

use yii\web\Response;

/**
 * @author    Percipio.London
 * @package   Seomatic
 * @since     3.0.0
 */

class SettingsController extends Controller
{
    // Constants
    // =========================================================================
    // Public Methods
    // =========================================================================
    /**
     * Dashboard display
     *
     * @param string|null $siteHandle
     *
     * @return Response The rendered result
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDashboard(string $siteHandle = null, bool $showWelcome = false): Response
    {
        $variables = [];

        $pluginName = Typesense::$plugin->getSettings()->pluginName;
        $templateTitle = Craft::t('typesense', 'Dashboard');

        $variables['controllerHandle'] = 'dashboard';
        $variables['pluginName'] = Typesense::$plugin->getSettings()->pluginName;
        $variables['title'] = $templateTitle;
        $variables['docTitle'] = sprintf('%s - %s', $pluginName, $templateTitle);
        $variables['selectedSubnavItem'] = 'dashboard';
        $variables['showWelcome'] = $showWelcome;

        // Render the template
        return $this->renderTemplate('typesense/dashboard/index', $variables);
    }

    /**
     * Settings display
     *
     *
     * @return Response The rendered result
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionPlugin(): Response
    {
        $variables = [];
        $pluginName = Typesense::$plugin->getSettings()->pluginName;
        $templateTitle = Craft::t('typesense', 'Plugin Settings');

        $variables['fullPageForm'] = true;
        $variables['pluginName'] = Typesense::$plugin->getSettings()->pluginName;
        $variables['title'] = $templateTitle;
        $variables['docTitle'] = sprintf('%s - %s', $pluginName, $templateTitle);
        $variables['selectedSubnavItem'] = 'plugin';
        $variables['settings'] = Typesense::$plugin->getSettings();

        // Render the template
        return $this->renderTemplate('typesense/settings/typesense-settings', $variables);
    }

    /**
     * Saves a plugin’s settings.
     *
     * @return Response|null
     * @throws NotFoundHttpException if the requested plugin cannot be found
     * @throws BadRequestHttpException
     * @throws MissingComponentException
     */

    public function actionSavePluginSettings()
    {
        $this->requirePostRequest();
        $pluginHandle = Craft::$app->getRequest()->getRequiredBodyParam('pluginHandle');
        $plugin = Craft::$app->getPlugins()->getPlugin($pluginHandle);

        if ($plugin === null) {
            throw new NotFoundHttpException('Plugin not found');
        }

        $settings = [
            'indexingEnabled' => Craft::$app->getRequest()->getBodyParam('indexingEnabled'),
            'apiKey' => Craft::$app->getRequest()->getBodyParam('apiKey'),
            'cluster' => Craft::$app->getRequest()->getBodyParam('cluster'),
            'clusterPort' => Craft::$app->getRequest()->getBodyParam('clusterPort'),
            'nearestNode' => Craft::$app->getRequest()->getBodyParam('nearestNode'),
            'port' => Craft::$app->getRequest()->getBodyParam('port'),
            'protocol' => Craft::$app->getRequest()->getBodyParam('protocol'),
            'searchOnlyApiKey' => Craft::$app->getRequest()->getBodyParam('searchOnlyApiKey'),
            'server' => Craft::$app->getRequest()->getBodyParam('server'),
            'serverType' => Craft::$app->getRequest()->getBodyParam('serverType'),
        ];

        if (!Craft::$app->getPlugins()->savePluginSettings($plugin, $settings)) {
            Craft::$app->getSession()->setError(Craft::t('app', "Couldn't save plugin settings."));

            // Send the plugin back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'plugin' => $plugin,
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('app', 'Plugin settings saved.'));

        Typesense::$plugin->getCollections()->saveCollections();

        return $this->redirectToPostedUrl();
    }
}
