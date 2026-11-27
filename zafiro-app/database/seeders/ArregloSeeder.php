<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Arreglo;
use App\Models\FormatoArreglo;
use App\Models\Ocasion;

class ArregloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = fake('es_ES');

        $formatos = FormatoArreglo::where('activo', true)->get()->keyBy('slug');
        $ocasionesIds = Ocasion::where('activo', true)->pluck('id')->all();

        $directorioImagenes = database_path('seeders/imagenes/arreglos');
        $archivosDisponibles = File::exists($directorioImagenes)
            ? collect(File::files($directorioImagenes))
                ->filter(fn ($archivo) => in_array(strtolower($archivo->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
                ->values()
            : collect();

        $arreglosCatalogo = [
            ['nombre' => 'Pasión Carmesí', 'formato' => 'ramo', 'precio_base' => 850.00, 'destacado' => true],
            ['nombre' => 'Brisa de Primavera', 'formato' => 'jarron', 'precio_base' => 1120.00, 'destacado' => true],
            ['nombre' => 'Sombrerera Imperial', 'formato' => 'caja-sombrerera', 'precio_base' => 1450.00, 'destacado' => true],
            ['nombre' => 'Encanto del Jardín', 'formato' => 'canasta', 'precio_base' => 980.00, 'destacado' => true],
            ['nombre' => 'Aurora Dorada', 'formato' => 'centro-de-mesa', 'precio_base' => 1290.00, 'destacado' => true],
            ['nombre' => 'Suspiro de Rosas', 'formato' => 'ramo', 'precio_base' => 790.00, 'destacado' => true],
            ['nombre' => 'Elegancia Silvestre', 'formato' => 'jarron', 'precio_base' => 1050.00, 'destacado' => false],
            ['nombre' => 'Dulce Celebración', 'formato' => 'caja-sombrerera', 'precio_base' => 1380.00, 'destacado' => false],
            ['nombre' => 'Tarde en el Valle', 'formato' => 'canasta', 'precio_base' => 940.00, 'destacado' => false],
            ['nombre' => 'Esencia Clásica', 'formato' => 'centro-de-mesa', 'precio_base' => 1180.00, 'destacado' => false],
            ['nombre' => 'Ternura Pura', 'formato' => 'ramo', 'precio_base' => 720.00, 'destacado' => false],
            ['nombre' => 'Refugio de Paz', 'formato' => 'jarron', 'precio_base' => 990.00, 'destacado' => false],
            ['nombre' => 'Cautiverio Floral', 'formato' => 'caja-sombrerera', 'precio_base' => 1520.00, 'destacado' => false],
            ['nombre' => 'Canasta del Alba', 'formato' => 'canasta', 'precio_base' => 890.00, 'destacado' => false],
            ['nombre' => 'Mesa de Gala', 'formato' => 'centro-de-mesa', 'precio_base' => 1600.00, 'destacado' => false],
            ['nombre' => 'Ramo Corazón', 'formato' => 'ramo', 'precio_base' => 880.00, 'destacado' => false],
            ['nombre' => 'Jarrón Cristalino', 'formato' => 'jarron', 'precio_base' => 1150.00, 'destacado' => false],
            ['nombre' => 'Sombrerera Rubí', 'formato' => 'caja-sombrerera', 'precio_base' => 1490.00, 'destacado' => false],
            ['nombre' => 'Canasta Serenidad', 'formato' => 'canasta', 'precio_base' => 960.00, 'destacado' => false],
            ['nombre' => 'Centro Armonía', 'formato' => 'centro-de-mesa', 'precio_base' => 1220.00, 'destacado' => false],
            ['nombre' => 'Bouquet Amanecer', 'formato' => 'ramo', 'precio_base' => 760.00, 'destacado' => false],
            ['nombre' => 'Jarrón Botánico', 'formato' => 'jarron', 'precio_base' => 1080.00, 'destacado' => false],
            ['nombre' => 'Sombrerera Perla', 'formato' => 'caja-sombrerera', 'precio_base' => 1410.00, 'destacado' => false],
            ['nombre' => 'Canasta Festiva', 'formato' => 'canasta', 'precio_base' => 910.00, 'destacado' => false],
            ['nombre' => 'Centro Esplendor', 'formato' => 'centro-de-mesa', 'precio_base' => 1350.00, 'destacado' => false],
        ];

        foreach ($arreglosCatalogo as $idx => $item) {
            $formato = $formatos->get($item['formato']) ?? $formatos->first();
            $slug = Str::slug($item['nombre']);
            $skuBase = 'ARR-' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT);

            $arreglo = Arreglo::updateOrCreate(
                ['slug' => $slug],
                [
                    'formato_arreglo_id' => $formato->id,
                    'sku' => $skuBase,
                    'nombre' => $item['nombre'],
                    'descripcion' => $faker->paragraph(2),
                    'precio_base' => $item['precio_base'],
                    'destacado' => $item['destacado'],
                    'activo' => true,
                ]
            );

            // 1 a 3 ocasiones por arreglo
            $cantidadOcasiones = ($idx % 3) + 1;
            $seleccionOcasiones = collect($ocasionesIds)->shuffle()->take($cantidadOcasiones)->values()->all();
            $arreglo->ocasiones()->sync($seleccionOcasiones);

            // 1 o 2 imágenes por arreglo copiadas a disco 'public' con UUID
            $arreglo->imagenes()->delete();
            $numImagenes = ($idx % 2 === 0) ? 2 : 1;

            for ($imgIdx = 0; $imgIdx < $numImagenes; $imgIdx++) {
                $extension = 'webp';
                $contenido = '';

                if ($archivosDisponibles->isNotEmpty()) {
                    $archivoOrigen = $archivosDisponibles[($idx + $imgIdx) % $archivosDisponibles->count()];
                    $extension = strtolower($archivoOrigen->getExtension()) ?: 'webp';
                    $contenido = file_get_contents($archivoOrigen->getRealPath());
                } else {
                    // Respaldo mínimo si aún no se colocaron archivos en la carpeta
                    $contenido = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
                    $extension = 'gif';
                }

                $ruta = 'arreglos/' . Str::uuid() . '.' . $extension;
                Storage::disk('public')->put($ruta, $contenido);

                $arreglo->imagenes()->create([
                    'ruta' => $ruta,
                    'es_principal' => $imgIdx === 0,
                    'orden' => $imgIdx + 1,
                ]);
            }
        }
    }
}
