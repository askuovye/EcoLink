<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <section class="relative w-full min-h-screen flex items-center justify-center bg-gradient-to-b from-green-50 to-white text-gray-900 overflow-hidden px-6 md:px-12">
        <div class="relative z-20 w-full max-w-6xl px-6 md:px-8 flex flex-col lg:flex-row items-center gap-12">

            
            <div class="w-full lg:w-1/2 flex items-center justify-center">
                <img src="<?php echo e(asset('images/hero-illustration.png')); ?>" class="w-64 h-64 md:w-72 md:h-72 object-contain" alt="Ilustração EcoLink">
            </div>

            
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                <h1 class="w-full text-4xl sm:text-5xl md:text-6xl lg:text-6xl font-extrabold leading-tight mb-4">
                    Conecte-se ao <span class="text-green-600">EcoLink</span> 🌿
                </h1>

                <p class="max-w-xl text-base sm:text-lg md:text-xl text-gray-700 mb-8">
                    Encontre pontos de descarte próximos, aprenda a separar resíduos corretamente e contribua para um planeta mais limpo.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start">
                    <!-- Botão principal (corrigido) -->
                    <a href="<?php echo e(route('map')); ?>"
                    class="inline-flex items-center gap-3 bg-white text-gray-900 border border-gray-200 px-6 py-3 rounded-full text-lg font-semibold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        <!-- ícone (inline svg) -->
                        <span>Ver Mapa de Descarte</span>
                    </a>

                    <!-- Botão secundário destacado e clean -->
                    <a href="<?php echo e(route('points.index')); ?>#sobre"
                    class="inline-flex items-center gap-3 border-2 border-green-600 text-green-700 px-6 py-3 rounded-full text-lg font-semibold hover:bg-green-600 hover:text-white transition transform hover:-translate-y-0.5">
                        Pontos de Coleta Disponíveis
                    </a>
                </div>
            </div>

        </div>

        
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 text-green-600 animate-bounce">
            <i data-lucide="chevrons-down" class="w-8 h-8"></i>
        </div>

        
        <style>
            /* força min-height caso algum pai limite */
            .min-h-screen { min-height: 100vh !important; }
            /* evita que botões fiquem com weird background por herança */
            .min-h-screen a { background-clip: padding-box; }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                try { lucide.createIcons(); } catch(e) { /* lucide may not be present */ }
            });
        </script>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // garante que os ícones apareçam
            try { lucide.createIcons(); } catch(e) { console.warn('lucide não carregado', e); }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\yikuu\Documents\EcoLinkProject(fudido)\ecolink\resources\views/home.blade.php ENDPATH**/ ?>