<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Attributes;
use App\Models\Deceased;
use App\Models\Order;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\Settings;
use App\Models\Site;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    /**
     * menuPermissions
     *
     * @return Illuminate\Http\Response
     */
    public function menuPermissions()
    {
        $canViewAttributes = Gate::inspect('viewAny', Attributes::class)->allowed();
        $canViewDeceased = Gate::inspect('viewAny', Deceased::class)->allowed();
        $canViewProducts = Gate::inspect('viewAny', Products::class)->allowed();
        $canViewOrders = Gate::inspect('viewAny', Order::class)->allowed();
        $canViewProductCategories = Gate::inspect('viewAny', ProductCategory::class)->allowed();
        $canViewSettings = Gate::inspect('viewAny', Settings::class)->allowed();
        $canViewSites = Gate::inspect('viewAny', Site::class)->allowed();
        $canViewSuppliers = Gate::inspect('viewAny', Supplier::class)->allowed();
        $canViewUsers = Gate::inspect('viewAny', User::class)->allowed();
        $canViewArticles = Gate::inspect('viewAny', Article::class)->allowed();
        $canViewFaqs = Gate::inspect('viewAny', Article::class)->allowed();
        $canViewMemorialisations = Gate::inspect('viewAny', Article::class)->allowed();

        return response()->json([
            'canViewDeceased' => $canViewDeceased,
            'canViewProducts' => $canViewProducts,
            'canViewOrders' => $canViewOrders,
            'canViewProductCategories' => $canViewProductCategories,
            'canViewSettings' => $canViewSettings,
            'canViewSites' => $canViewSites,
            'canViewSuppliers' => $canViewSuppliers,
            'canViewUsers' => $canViewUsers,
            'canViewAttributes' => $canViewAttributes,
            'canViewArticles' => $canViewArticles,
            'canViewFaqs' => $canViewFaqs,
            'canViewMemorialisations' => $canViewMemorialisations,
        ]);
    }
}
