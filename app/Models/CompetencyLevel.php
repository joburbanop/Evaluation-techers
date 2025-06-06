<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class CompetencyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'name',
        'code',
        'min_score',
        'max_score',
        'description'
    ];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer'
    ];


    public function test ()
    {
        return $this->belongsTo(Test::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Limpiar caché cuando se crea, actualiza o elimina un nivel
        static::created(function ($level) {
            static::clearCache();
        });

        static::updated(function ($level) {
            static::clearCache();
        });

        static::deleted(function ($level) {
            static::clearCache();
        });

        static::saving(function ($level) {
            // Validar que min_score sea menor que max_score
            if ($level->min_score >= $level->max_score) {
                throw ValidationException::withMessages([
                    'general' => ['La puntuación mínima debe ser menor que la puntuación máxima.']
                ]);
            }

            // Validar que no haya superposición de rangos
            $overlappingLevel = static::where('id', '!=', $level->id)
                ->where(function ($query) use ($level) {
                    $query->whereBetween('min_score', [$level->min_score, $level->max_score])
                        ->orWhereBetween('max_score', [$level->min_score, $level->max_score])
                        ->orWhere(function ($q) use ($level) {
                            $q->where('min_score', '<=', $level->min_score)
                                ->where('max_score', '>=', $level->max_score);
                        });
                })
                ->first();

            if ($overlappingLevel) {
                throw ValidationException::withMessages([
                    'general' => ['Existe superposición con otro nivel de competencia.']
                ]);
            }
        });
    }

    /**
     * Obtiene todos los niveles de competencia con caché
     */
    public static function getAllLevels()
    {
        return Cache::remember('competency_levels', 3600, function () {
            return static::orderBy('min_score', 'asc')->get();
        });
    }

    /**
     * Obtiene un nivel específico por puntuación con caché
     */
    public static function getLevelByScore(int $score): ?self
    {
        $cacheKey = "competency_level_score_{$score}";

        return Cache::remember($cacheKey, 3600, function () use ($score) {
        return static::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
        });
    }

    /**
     * Limpia toda la caché relacionada con los niveles
     */
    public static function clearCache()
    {
        Cache::forget('competency_levels');

        // Limpiar caché de niveles por puntuación
        $levels = static::all();
        foreach ($levels as $level) {
            for ($score = $level->min_score; $score <= $level->max_score; $score++) {
                Cache::forget("competency_level_score_{$score}");
            }
        }
    }

    public static function initializeLevels(): void
    {
        //inicializar la variable test dame el codigo
        $test = Test::where('name', 'Evaluación de Competencias Digitales Docentes')->first();


        $levels = [
            [
                'test_id'=> $test->id,
                'name' => 'Novato',
                'code' => 'A1',
                'min_score' => 0,
                'max_score' => 19,
                'description' => 'Muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente. '






            ],
            [
                'test_id'=> $test->id,
                'name' => 'Explorador',
                'code' => 'A2',
                'min_score' => 20,
                'max_score' => 33,
                'description' => 'Poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Integrador',
                'code' => 'B1',
                'min_score' => 34,
                'max_score' => 49,
                'description' => 'Experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Experto',
                'code' => 'B2',
                'min_score' => 50,
                'max_score' => 65,
                'description' => 'Utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'
            ],
            [
                'test_id'=> $test->id,

                'name' => 'Líder',
                'code' => 'C1',
                'min_score' => 66,
                'max_score' => 80,
                'description' => 'Capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Pionero',
                'code' => 'C2',
                'min_score' => 81,
                'max_score' => 100,
                'description' => 'Cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes'
            ],
        ];

        foreach ($levels as $level) {
            static::firstOrCreate(
                ['code' => $level['code']],
                $level
            );
        }

        // Limpiar caché después de inicializar
        static::clearCache();
    }
}
