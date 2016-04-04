<?php

namespace TypiCMS\Modules\Settings\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Krucas\Notification\Facades\Notification;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Settings\Repositories\SettingInterface;
use Artisan;

class AdminController extends BaseAdminController
{
    public function __construct(SettingInterface $setting)
    {
        parent::__construct($setting);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = $this->repository->all();

        return view('settings::admin.index')
            ->with(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = Request::all();
        $this->repository->store($data);

        return redirect()->route('admin.settings.index');
    }

    /**
     * Delete image.
     *
     * @return null
     */
    public function deleteImage()
    {
        $this->repository->deleteImage();
    }

    /**
     * Clear app cache.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        Cache::flush();
        Artisan::call('config:clear');
        Notification::success(trans('settings::global.Cache cleared').'.');

        return redirect()->route('admin.settings.index');
    }

    /**
     * Caches the config using artisan - used for production environments without shell.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function configCache()
    {
        Artisan::call('config:cache');
        $output = Artisan::output();
        $output = str_replace("\n", "<br>", $output);
        Notification::success(trans('settings::global.Config cached') . ' - ' . $output);

        return redirect()->route('admin.settings.index');
    }

    /**
     * imgrates the database using artisan - used for production environments without shell.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dbMigrate()
    {
        Artisan::call('migrate');
        $output = Artisan::output();
        $output = str_replace("\n", "<br>", $output);
        Notification::success(trans('settings::global.Database migrated') . ' - ' . $output);

        return redirect()->route('admin.settings.index');
    }
    
}
