@extends('layouts.layoutbaseproyecto')

@section('styles')
<style>
.confirmar-pagar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2.5rem 1.5rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Encabezado */
.confirmar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    flex-wrap: wrap;
    gap: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.confirmar-header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #1a1a1a;
    letter-spacing: -0.3px;
    position: relative;
}

.confirmar-header h1::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 40px;
    height: 2px;
    background: #0ea5e9;
    border-radius: 1px;
}

/* Tarjeta principal */
.card-confirmar-pagar {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid #f5f5f5;
    max-width: 800px;
    margin: 0 auto;
}

.card-body {
    padding: 2rem;
}

/* Secciones */
.seccion {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f5f5f5;
}

.seccion:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.seccion-titulo {
    font-size: 1.25rem;
    font-weight: 600;
    color: #222;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.seccion-titulo::before {
    content: "";
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #0ea5e9;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 700;
}

.seccion-1::before { content: "1"; }
.seccion-2::before { content: "2"; }
.seccion-3::before { content: "3"; }

/* Opciones de pago */
.opcion-pago {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.opcion-pago:hover {
    border-color: #0ea5e9;
    background: #f8fafc;
}

.opcion-pago.active {
    border-color: #0ea5e9;
    background: #f0f9ff;
}

.opcion-pago input[type="radio"] {
    width: 20px;
    height: 20px;
    accent-color: #0ea5e9;
}

/* Resumen de reserva */
.resumen-reserva {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.resumen-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.resumen-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0ea5e9;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

/* Botón principal */
.btn-pagar {
    background: linear-gradient(135deg, #0ea5e9, #3b82f6);
    color: white;
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
}

.btn-pagar:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
}

/* Mensaje de advertencia */
.advertencia {
    background: #fef3c7;
    border-left: 4px solid #d97706;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: #92400e;
}

/* Responsive */
@media (max-width: 768px) {
    .seccion {
        padding: 1rem 0;
    }
    
    .card-body {
        padding: 1.5rem;
    }
}
</style>
@endsection

@section('content')
<div class="confirmar-pagar-container">
    <div class="confirmar-header">
        <h1>Confirmar y pagar</h1>
        <a href="{{ route('reservas.mi-lista') }}" class="btn-volver">Volver a mis compras</a>
    </div>

    <div class="card-confirmar-pagar">
        <div class="card-body">
            <!-- Sección 1: Elegir cuándo pagar -->
            <div class="seccion seccion-1">
                <h2 class="seccion-titulo">1. Elige cuándo quieres pagar</h2>
                
                <div class="opcion-pago active">
                    <input type="radio" name="metodo_pago" value="ahora" checked>
                    <div>
                        <strong>Paga 476,00 € ahora</strong>
                        <p style="color: #666; font-size: 0.9rem;">Pago único y completo</p>
                    </div>
                </div>
                
                <div class="opcion-pago">
                    <input type="radio" name="metodo_pago" value="plazos">
                    <div>
                        <strong>Paga en 3 plazos con Klarna</strong>
                        <p style="color: #666; font-size: 0.9rem;">158,66 € × 3 sin intereses (0% TAE)</p>
                        <a href="#" style="color: #0ea5e9; font-size: 0.875rem; text-decoration: underline;">Más información</a>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Añade un método de pago -->
            <div class="seccion seccion-2">
                <h2 class="seccion-titulo">2. Añade un método de pago</h2>
                
                <div class="resumen-reserva">
                    <div class="resumen-item">
                        <span>Tarjeta de crédito/débito</span>
                        <span>•••• •••• •••• 1234</span>
                    </div>
                    <div class="resumen-item">
                        <span>Titular</span>
                        <span>{{ Auth::user()->Nombre }} {{ Auth::user()->Apellidos }}</span>
                    </div>
                    <div class="resumen-item">
                        <span>Fecha de caducidad</span>
                        <span>12/27</span>
                    </div>
                </div>
                
                <p style="color: #666; font-size: 0.9rem; margin-top: 1rem;">
                    <strong>🔒 Pago seguro:</strong> Tus datos están protegidos con cifrado SSL.
                </p>
            </div>

            <!-- Sección 3: Consulta tu reserva -->
            <div class="seccion seccion-3">
                <h2 class="seccion-titulo">3. Consulta tu reserva</h2>
                
                <div class="resumen-reserva">
                    <div class="resumen-item">
                        <div>
                            <strong>Inhala Hotel Garden</strong>
                            <p style="color: #666; font-size: 0.9rem;">Habitación Petit</p>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                <span>⭐ 4,79 (34)</span>
                            </div>
                        </div>
                        <img src="https://via.placeholder.com/80x80?text=Hotel" alt="Hotel" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    </div>
                    
                    <div class="resumen-item">
                        <span>Febrero 2026</span>
                        <span>13–15 feb 2026</span>
                    </div>
                    
                    <div class="resumen-item">
                        <span>Viajeros</span>
                        <span>1 adulto</span>
                    </div>
                    
                    <div class="resumen-item">
                        <span>Ubicación</span>
                        <span>Calle de San Bernardo, 1<br>28013, Madrid, Comunidad de Madrid</span>
                    </div>
                    
                    <div class="resumen-item" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                        <span><strong>Detalles del precio</strong></span>
                        <span></span>
                    </div>
                    
                    <div class="resumen-item">
                        <span>2 noches por 216,37 €</span>
                        <span>432,73 €</span>
                    </div>
                    <div class="resumen-item">
                        <span>Impuestos y tarifas</span>
                        <span>43,27 €</span>
                    </div>
                    
                    <div class="resumen-total">
                        <span>Total EUR</span>
                        <span>{{ number_format($detalle->Precio, 2) }} €</span>
                    </div>
                    
                    <a href="#" style="color: #0ea5e9; font-size: 0.875rem; text-decoration: underline;">Desglose del precio</a>
                </div>
            </div>

            <!-- Advertencia -->
            <div class="advertencia">
                <strong>⚠️ ¡Qué suerte! Este alojamiento suele estar reservado.</strong>
            </div>

            <!-- Botón de pago -->
            <button type="submit" class="btn-pagar">
                ✅ Confirmar y pagar
            </button>
        </div>
    </div>
</div>
@endsection