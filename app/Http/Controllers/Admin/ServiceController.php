<?php
/**
 * Created by PhpStorm.
 * User: chenyihong
 * Date: 16/8/3
 * Time: 16:15
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ServiceRepository;
use Leo108\CAS\Models\Service;

class ServiceController extends Controller
{
    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * ServiceController constructor.
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Request $request)
    {
        $page     = $request->get('page', 1);
        $limit    = 20;
        $search   = $request->get('search', '');
        $services = $this->serviceRepository->getList($search, $page, $limit);

        return view(
            'admin.service',
            [
                'services' => $services,
                'query'    => [
                    'search' => $search,
                ],
            ]
        );
    }

    public function store(Request $request)
    {
        $all          = $request->all();
        $all['hosts'] = $this->formatHosts($request);
        $service      = $this->serviceRepository->create($all);
        $service->load('hosts');

        return response()->json(['msg' => trans('admin.service.add_ok')]);
    }

    public function update(Service $service, Request $request)
    {
        $all          = $request->all();
        $all['hosts'] = $this->formatHosts($request);
        $this->serviceRepository->update($all, $service);

        return response()->json(['msg' => trans('admin.service.edit_ok')]);
    }

    protected function formatHosts(Request $request)
    {
        $hosts  = explode("\n", $request->get('hosts', ''));
        $result = [];
        foreach ($hosts as $host) {
            $host = trim($host);
            if (empty($host)) {
                continue;
            }

            if (filter_var($host, FILTER_VALIDATE_URL)) {
                $realHost = parse_url($host, PHP_URL_HOST);
                $result[] = $realHost;
            } else {
                $result[] = $host;
            }
        }

        return $result;
    }
}