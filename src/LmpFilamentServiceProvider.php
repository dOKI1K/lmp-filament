<?php

namespace Lampminds\Filament;

use Illuminate\Support\ServiceProvider;

class LmpFilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();
    }

    public function register()
    {
        //
    }

    protected function registerPublishables(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        if (empty(glob(app_path('Console/Commands/*')))) {
            $this->publishes([
                __DIR__ . '/../app/Console/Commands/Filamodel.php' => app_path('Console/Commands/Filamodel.php'),
                __DIR__ . '/../app/Console/Commands/stubs/Resources.stub' => app_path('Console/Commands/stubs/Resource.stub'),
            ], 'commands');
        }

        if (empty(glob(app_path('Models/BaseModel.php'))) && empty(glob(app_path('Models/Parameter.php')))) {
            $this->publishes([
                __DIR__ . '/../app/Models/BaseModel.php' => app_path('Models/BaseModel.php'),
                __DIR__ . '/../app/Models/Parameter.php' => app_path('Models/Parameter.php'),
            ], 'models');
        }

        if (empty(glob(app_path('Traits/AuditTrait.php')))) {
            $this->publishes([
                __DIR__ . '/../app/Traits/AuditTrait.php' => app_path('Traits/AuditTrait.php'),
            ], 'traits');
        }

        if (empty(glob(app_path('Filament/Lampminds/*')))) {
            $this->publishes([
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormArPhone.php' => app_path('Filament/Lampminds/FormComponents/LmpFormArPhone.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormCode.php' => app_path('Filament/Lampminds/FormComponents/LmpFormCode.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormCreatedByStamp.php' => app_path('Filament/Lampminds/FormComponents/LmpFormCreatedByStamp.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormDate.php' => app_path('Filament/Lampminds/FormComponents/LmpFormDate.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormEmail.php' => app_path('Filament/Lampminds/FormComponents/LmpFormEmail.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormFullName.php' => app_path('Filament/Lampminds/FormComponents/LmpFormFullName.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormLink.php' => app_path('Filament/Lampminds/FormComponents/LmpFormLink.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormLocation.php' => app_path('Filament/Lampminds/FormComponents/LmpFormLocation.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormNaPhone.php' => app_path('Filament/Lampminds/FormComponents/LmpFormNaPhone.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormQuestion.php' => app_path('Filament/Lampminds/FormComponents/LmpFormQuestion.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormRichEditor.php' => app_path('Filament/Lampminds/FormComponents/LmpFormRichEditor.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormSlug.php' => app_path('Filament/Lampminds/FormComponents/LmpFormSlug.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormSnake.php' => app_path('Filament/Lampminds/FormComponents/LmpFormSnake.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormTextArea.php' => app_path('Filament/Lampminds/FormComponents/LmpFormTextArea.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormTimeStamp.php' => app_path('Filament/Lampminds/FormComponents/LmpFormTimeStamp.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormTitle.php' => app_path('Filament/Lampminds/FormComponents/LmpFormTitle.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormToggle.php' => app_path('Filament/Lampminds/FormComponents/LmpFormToggle.php'),
                __DIR__ . '/../app/Filament/Lampminds/FormComponents/LmpFormUpdatedByStamp.php' => app_path('Filament/Lampminds/FormComponents/LmpFormUpdatedByStamp.php'),
                __DIR__ . '/../app/Filament/Lampminds/Resources/LmpCreateRecord' => app_path('Filament/Lampminds/Resources/LmpCreateRecord'),
                __DIR__ . '/../app/Filament/Lampminds/Resources/LmpEditRecord' => app_path('Filament/Lampminds/Resources/LmpEditRecord'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableArPhone' => app_path('Filament/Lampminds/TableComponents/LmpTableArPhone'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableCreatedByStamp' => app_path('Filament/Lampminds/TableComponents/LmpTableCreatedByStamp'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableDate' => app_path('Filament/Lampminds/TableComponents/LmpTableDate'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableLocation' => app_path('Filament/Lampminds/TableComponents/LmpTableLocation'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableNaPhone' => app_path('Filament/Lampminds/TableComponents/LmpTableNaPhone'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableRelationCounter' => app_path('Filament/Lampminds/TableComponents/LmpTableRelationCounter'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableTimeStamp' => app_path('Filament/Lampminds/TableComponents/LmpTableTimeStamp'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableTitle' => app_path('Filament/Lampminds/TableComponents/LmpTableTitle'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableToggle' => app_path('Filament/Lampminds/TableComponents/LmpTableToggle'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableUpdatedAt' => app_path('Filament/Lampminds/TableComponents/LmpTableUpdatedAt'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableUpdatedBy' => app_path('Filament/Lampminds/TableComponents/LmpTableUpdatedBy'),
                __DIR__ . '/../app/Filament/Lampminds/TableComponents/LmpTableUpdatedByStamp' => app_path('Filament/Lampminds/TableComponents/LmpTableUpdatedByStamp'),
            ], 'filament');
        }

        if (empty(glob(app_path('Helpers/*')))) {
            $this->publishes([
                __DIR__ . '/../app/Helpers/helpers-common.php' => app_path('Helpers/helpers-common.php'),
                __DIR__ . '/../app/Helpers/helpers-log.php' => app_path('Helpers/helpers-log.php'),
                __DIR__ . '/../app/Helpers/helpers-useragent.php' => app_path('Helpers/helpers-useragent.php'),
                __DIR__ . '/../app/Helpers/helpers-parameters.php' => app_path('Helpers/helpers-parameters.php'),
            ], 'helpers');
        }
    }
}
