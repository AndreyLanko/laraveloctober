<?php namespace Perevorot\LaravelOctober\Extensions;

use Blade;

class BladeExtensions
{
    public static function extend()
    {
        Blade::directive('spaceless', function() {
            return '<?php ob_start() ?>';
        });

        Blade::directive('endspaceless', function() {
            return "<?php echo preg_replace('/>\\s+</', '><', ob_get_clean()); ?>";
        });
    }
}
