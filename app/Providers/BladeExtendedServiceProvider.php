<?php
// --------------------------------------------------------------------------
namespace App\Providers;
// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Include partial files with relative path
// Based on https://stackoverflow.com/a/60020948
// --------------------------------------------------------------------------
class BladeExtendedServiceProvider extends ServiceProvider {
    // ----------------------------------------------------------------------
    /**
     * Register services.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function register(){}
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
     * Bootstrap services.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function boot(){
        // ------------------------------------------------------------------
        Blade::directive( 'relativeInclude', function( $args ){
            $args = Blade::stripParentheses( $args );
            // --------------------------------------------------------------
            $viewBasePath = Blade::getPath();
            foreach ($this->app['config']['view.paths'] as $path) {
                if (substr($viewBasePath,0,strlen($path)) === $path) {
                    $viewBasePath = substr($viewBasePath,strlen($path));
                    break;
                }
            }
            // --------------------------------------------------------------
            $viewBasePath = dirname(trim($viewBasePath,'\/'));
            $args = substr_replace($args, $viewBasePath.'.', 1, 0);
            return "<?php echo \$__env->make({$args}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
