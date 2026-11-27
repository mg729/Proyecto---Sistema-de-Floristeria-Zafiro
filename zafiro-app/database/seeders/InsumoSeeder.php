<?php

namespace Database\Seeders;

use App\Models\CategoriaInsumo;
use App\Models\DetalleBase;
use App\Models\DetalleFlor;
use App\Models\FormatoArreglo;
use App\Models\Insumo;
use App\Models\UnidadMedida;

use Illuminate\Database\Seeder;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        // Se buscan por slug o abreviatura, NUNCA por ID (en TiDB los ID no son consecutivos)
        $categorias = CategoriaInsumo::pluck('id', 'slug');
        $unidades = UnidadMedida::pluck('id', 'abreviatura');
        $formatos = FormatoArreglo::pluck('id', 'slug');

        foreach ($this->insumos() as $dato) {
            if (! isset($categorias[$dato['categoria']])) {
                throw new \RuntimeException("No existe la categoria '{$dato['categoria']}'. Revise la Ola 1.");
            }

            if (! isset($unidades[$dato['unidad']])) {
                throw new \RuntimeException("No existe la unidad '{$dato['unidad']}'. Revise la Ola 1.");
            }

            $insumo = Insumo::create([
                'categoria_insumo_id' => $categorias[$dato['categoria']],
                'unidad_medida_id' => $unidades[$dato['unidad']],
                'modelo_3d_id' => null,   // el I3 lo asigna cuando tenga sus modelos 3D
                'sku' => $dato['sku'],
                'nombre' => $dato['nombre'],
                'descripcion' => $dato['descripcion'],
                'color_nombre' => $dato['color_nombre'] ?? null,
                'color_hex' => $dato['color_hex'] ?? null,
                'precio_componente' => $dato['precio'],
                'imagen' => null,
                'visible_en_creador' => $dato['visible'] ?? true,
                'max_por_diseno' => $dato['max'] ?? null,
                'activo' => true,
            ]);

            // Solo las bases llevan detalles_base
            if (isset($dato['base'])) {
                $formatoId = $formatos[$dato['base']['formato']] ?? null;

                if (! $formatoId) {
                    $formatoId = $formatos->first();
                    $this->command->warn("No existe el formato '{$dato['base']['formato']}' (I2). Se usó otro formato para {$dato['sku']}.");
                }

                DetalleBase::create([
                    'insumo_id' => $insumo->id,
                    'formato_arreglo_id' => $formatoId,
                    'material' => $dato['base']['material'],
                    'capacidad_min_tallos' => $dato['base']['min'],
                    'capacidad_max_tallos' => $dato['base']['max'],
                    'diametro_apertura_cm' => $dato['base']['diametro'],
                    'altura_cm' => $dato['base']['altura'],
                    'cargo_armado' => $dato['base']['cargo'],
                ]);
            }

            // Solo las flores y los follajes llevan detalles_flor
            if (isset($dato['flor'])) {
                DetalleFlor::create([
                    'insumo_id' => $insumo->id,
                    'nombre_cientifico' => $dato['flor']['cientifico'],
                    'origen' => $dato['flor']['origen'],
                    'vida_florero_dias' => $dato['flor']['vida'],
                    'temporada' => $dato['flor']['temporada'],
                    'cuidados' => $dato['flor']['cuidados'],
                ]);
            }
        }
    }

    // Los 30 insumos acordados con el equipo (los SKU los usan el I2 en recetas y el I3 en diseños)
    private function insumos(): array
    {
        $cuidadoFlor = 'Cortar el tallo en diagonal, cambiar el agua cada dos días y mantener lejos del sol directo.';
        $cuidadoFollaje = 'Mantener en agua fresca y retirar las hojas que queden bajo el nivel del agua.';

        return [
            // Bases (paso 1 del Creador)
            ['sku' => 'BAS-JAR-VID', 'nombre' => 'Jarrón de vidrio', 'categoria' => 'bases', 'unidad' => 'und', 'precio' => 180.00, 'max' => 1,
                'descripcion' => 'Jarrón cilíndrico de vidrio transparente.',
                'base' => ['formato' => 'jarron', 'material' => 'Vidrio', 'min' => 5, 'max' => 25, 'diametro' => 10.00, 'altura' => 25.00, 'cargo' => 50.00]],
            ['sku' => 'BAS-CAJ-SOM', 'nombre' => 'Caja sombrerera', 'categoria' => 'bases', 'unidad' => 'und', 'precio' => 220.00, 'max' => 1,
                'descripcion' => 'Caja redonda forrada con tapa, para arreglos compactos.',
                'base' => ['formato' => 'caja-sombrerera', 'material' => 'Cartón rígido forrado', 'min' => 10, 'max' => 40, 'diametro' => 20.00, 'altura' => 15.00, 'cargo' => 70.00]],
            ['sku' => 'BAS-CAN-MIM', 'nombre' => 'Canasta de mimbre', 'categoria' => 'bases', 'unidad' => 'und', 'precio' => 160.00, 'max' => 1,
                'descripcion' => 'Canasta tejida con asa, con espuma floral.',
                'base' => ['formato' => 'canasta', 'material' => 'Mimbre', 'min' => 8, 'max' => 30, 'diametro' => 25.00, 'altura' => 12.00, 'cargo' => 60.00]],
            ['sku' => 'BAS-CEN-MES', 'nombre' => 'Base para centro de mesa', 'categoria' => 'bases', 'unidad' => 'und', 'precio' => 150.00, 'max' => 1,
                'descripcion' => 'Base baja de cerámica para centros de mesa.',
                'base' => ['formato' => 'centro-de-mesa', 'material' => 'Cerámica con espuma floral', 'min' => 10, 'max' => 35, 'diametro' => 18.00, 'altura' => 10.00, 'cargo' => 65.00]],
            ['sku' => 'BAS-ENV-RAM', 'nombre' => 'Envoltura para ramo', 'categoria' => 'bases', 'unidad' => 'und', 'precio' => 60.00, 'max' => 1,
                'descripcion' => 'Papel coreano y celofán para armar ramos.',
                'base' => ['formato' => 'ramo', 'material' => 'Papel coreano y celofán', 'min' => 6, 'max' => 36, 'diametro' => 30.00, 'altura' => 40.00, 'cargo' => 40.00]],

            // Flores (paso 2 del Creador)
            ['sku' => 'FLR-ROS-ROJ', 'nombre' => 'Rosa roja', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 25.00, 'max' => 24,
                'color_nombre' => 'Rojo', 'color_hex' => '#C0392B', 'descripcion' => 'Rosa de tallo largo, color rojo intenso.',
                'flor' => ['cientifico' => 'Rosa sp.', 'origen' => 'Ecuador', 'vida' => 7, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-ROS-BLA', 'nombre' => 'Rosa blanca', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 25.00, 'max' => 24,
                'color_nombre' => 'Blanco', 'color_hex' => '#F8F8F2', 'descripcion' => 'Rosa de tallo largo, color blanco.',
                'flor' => ['cientifico' => 'Rosa sp.', 'origen' => 'Ecuador', 'vida' => 7, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-ROS-ROS', 'nombre' => 'Rosa rosada', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 25.00, 'max' => 24,
                'color_nombre' => 'Rosado', 'color_hex' => '#F1948A', 'descripcion' => 'Rosa de tallo largo, color rosado.',
                'flor' => ['cientifico' => 'Rosa sp.', 'origen' => 'Ecuador', 'vida' => 7, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-GIR-AMA', 'nombre' => 'Girasol', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 35.00, 'max' => 12,
                'color_nombre' => 'Amarillo', 'color_hex' => '#F1C40F', 'descripcion' => 'Girasol de flor grande.',
                'flor' => ['cientifico' => 'Helianthus annuus', 'origen' => 'Honduras', 'vida' => 8, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-LIR-BLA', 'nombre' => 'Lirio blanco', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 40.00, 'max' => 12,
                'color_nombre' => 'Blanco', 'color_hex' => '#FDFEFE', 'descripcion' => 'Lirio aromático de varias flores por tallo.',
                'flor' => ['cientifico' => 'Lilium candidum', 'origen' => 'Colombia', 'vida' => 10, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-TUL-ROS', 'nombre' => 'Tulipán rosado', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 45.00, 'max' => 15,
                'color_nombre' => 'Rosado', 'color_hex' => '#F5B7B1', 'descripcion' => 'Tulipán importado de color rosado.',
                'flor' => ['cientifico' => 'Tulipa gesneriana', 'origen' => 'Países Bajos', 'vida' => 6, 'temporada' => 'Diciembre a mayo', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-ORQ-MOR', 'nombre' => 'Orquídea morada', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 60.00, 'max' => 6,
                'color_nombre' => 'Morado', 'color_hex' => '#8E44AD', 'descripcion' => 'Vara de orquídea con varias flores.',
                'flor' => ['cientifico' => 'Phalaenopsis sp.', 'origen' => 'Honduras', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-CLA-ROJ', 'nombre' => 'Clavel rojo', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 15.00, 'max' => 24,
                'color_nombre' => 'Rojo', 'color_hex' => '#CB4335', 'descripcion' => 'Clavel de color rojo.',
                'flor' => ['cientifico' => 'Dianthus caryophyllus', 'origen' => 'Colombia', 'vida' => 12, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-GER-NAR', 'nombre' => 'Gerbera naranja', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 20.00, 'max' => 18,
                'color_nombre' => 'Naranja', 'color_hex' => '#E67E22', 'descripcion' => 'Gerbera de color naranja vivo.',
                'flor' => ['cientifico' => 'Gerbera jamesonii', 'origen' => 'Honduras', 'vida' => 7, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-HOR-AZU', 'nombre' => 'Hortensia azul', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 55.00, 'max' => 6,
                'color_nombre' => 'Azul', 'color_hex' => '#5DADE2', 'descripcion' => 'Hortensia de cabeza grande, color azul.',
                'flor' => ['cientifico' => 'Hydrangea macrophylla', 'origen' => 'Colombia', 'vida' => 7, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-AST-LIL', 'nombre' => 'Astromelia lila', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 18.00, 'max' => 18,
                'color_nombre' => 'Lila', 'color_hex' => '#C39BD3', 'descripcion' => 'Astromelia de color lila, de larga duración.',
                'flor' => ['cientifico' => 'Alstroemeria aurea', 'origen' => 'Colombia', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-CRI-AMA', 'nombre' => 'Crisantemo amarillo', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 15.00, 'max' => 18,
                'color_nombre' => 'Amarillo', 'color_hex' => '#F4D03F', 'descripcion' => 'Crisantemo tipo pompón, color amarillo.',
                'flor' => ['cientifico' => 'Chrysanthemum morifolium', 'origen' => 'Honduras', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],
            ['sku' => 'FLR-PAN-BLA', 'nombre' => 'Paniculata', 'categoria' => 'flores', 'unidad' => 'tallo', 'precio' => 12.00, 'max' => 10,
                'color_nombre' => 'Blanco', 'color_hex' => '#FFFFFF', 'descripcion' => 'Flor pequeña de relleno, color blanco.',
                'flor' => ['cientifico' => 'Gypsophila paniculata', 'origen' => 'Ecuador', 'vida' => 10, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFlor]],

            // Follajes (paso 3 del Creador)
            ['sku' => 'FOL-EUC-VER', 'nombre' => 'Eucalipto', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 15.00, 'max' => 10,
                'color_nombre' => 'Verde grisáceo', 'color_hex' => '#7DA27E', 'descripcion' => 'Rama de eucalipto aromático.',
                'flor' => ['cientifico' => 'Eucalyptus cinerea', 'origen' => 'Honduras', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-HEL-VER', 'nombre' => 'Helecho cuero', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 10.00, 'max' => 10,
                'color_nombre' => 'Verde oscuro', 'color_hex' => '#2E7D32', 'descripcion' => 'Hoja de helecho resistente para relleno.',
                'flor' => ['cientifico' => 'Rumohra adiantiformis', 'origen' => 'Costa Rica', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-RUS-VER', 'nombre' => 'Ruscus', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 12.00, 'max' => 10,
                'color_nombre' => 'Verde', 'color_hex' => '#4CAF50', 'descripcion' => 'Rama de hojas pequeñas y brillantes.',
                'flor' => ['cientifico' => 'Ruscus aculeatus', 'origen' => 'Costa Rica', 'vida' => 21, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-MON-VER', 'nombre' => 'Hoja de monstera', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 20.00, 'max' => 4,
                'color_nombre' => 'Verde intenso', 'color_hex' => '#1B5E20', 'descripcion' => 'Hoja tropical grande y abierta.',
                'flor' => ['cientifico' => 'Monstera deliciosa', 'origen' => 'Honduras', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-PIT-VER', 'nombre' => 'Pittosporum', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 12.00, 'max' => 10,
                'color_nombre' => 'Verde', 'color_hex' => '#558B2F', 'descripcion' => 'Rama de follaje denso para dar volumen.',
                'flor' => ['cientifico' => 'Pittosporum tobira', 'origen' => 'Guatemala', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-ESP-VER', 'nombre' => 'Esparraguera', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 10.00, 'max' => 10,
                'color_nombre' => 'Verde claro', 'color_hex' => '#66BB6A', 'descripcion' => 'Follaje fino y ligero.',
                'flor' => ['cientifico' => 'Asparagus setaceus', 'origen' => 'Honduras', 'vida' => 10, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],
            ['sku' => 'FOL-OLI-VER', 'nombre' => 'Rama de olivo', 'categoria' => 'follajes', 'unidad' => 'rama', 'precio' => 18.00, 'max' => 8,
                'color_nombre' => 'Verde plateado', 'color_hex' => '#8D9F87', 'descripcion' => 'Rama de olivo de hojas alargadas.',
                'flor' => ['cientifico' => 'Olea europaea', 'origen' => 'Guatemala', 'vida' => 14, 'temporada' => 'Todo el año', 'cuidados' => $cuidadoFollaje]],

            // Complementos (paso 4 del Creador)
            ['sku' => 'COM-CHO-CAJ', 'nombre' => 'Caja de chocolates', 'categoria' => 'complementos', 'unidad' => 'und', 'precio' => 150.00, 'max' => 2,
                'descripcion' => 'Caja de 12 chocolates surtidos.'],
            ['sku' => 'COM-PEL-OSO', 'nombre' => 'Peluche de oso', 'categoria' => 'complementos', 'unidad' => 'und', 'precio' => 250.00, 'max' => 1,
                'descripcion' => 'Oso de peluche de 30 cm.'],
            ['sku' => 'COM-GLO-HEL', 'nombre' => 'Globo de helio', 'categoria' => 'complementos', 'unidad' => 'und', 'precio' => 80.00, 'max' => 3,
                'descripcion' => 'Globo metálico inflado con helio.'],
            ['sku' => 'COM-VIN-TIN', 'nombre' => 'Botella de vino tinto', 'categoria' => 'complementos', 'unidad' => 'und', 'precio' => 450.00, 'max' => 1,
                'descripcion' => 'Botella de vino tinto de 750 ml.'],

            // Materiales (no aparecen en el Creador)
            ['sku' => 'MAT-CIN-SAT', 'nombre' => 'Cinta satinada', 'categoria' => 'materiales', 'unidad' => 'm', 'precio' => 10.00, 'visible' => false,
                'descripcion' => 'Cinta satinada para amarrar ramos y cajas.'],
        ];
    }
}