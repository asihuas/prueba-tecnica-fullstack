<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import Message from 'primevue/message'
import Calendar from 'primevue/calendar'
import { FilterMatchMode } from '@primevue/core/api'

const apiBaseUrl = 'http://127.0.0.1:8000/api'

const equipos = ref([])
const equiposFiltrados = ref([])
const codigosTexto = ref('')
const noEncontrados = ref([])

const estadoSeleccionado = ref('Todos')
const tipoSeleccionado = ref('Todos')
const rangoFechas = ref(null) 

const buildFiltros = () => ({
  codigo: { value: null, matchMode: FilterMatchMode.CONTAINS },
  cliente: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tipo: { value: null, matchMode: FilterMatchMode.CONTAINS },
  estado: { value: null, matchMode: FilterMatchMode.CONTAINS },
  fecha_entrega: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const filtros = ref(buildFiltros())

const cargarEquipos = async () => {
  try {
    const { data } = await axios.get(`${apiBaseUrl}/equipos`)
    equipos.value = Array.isArray(data) ? data : []
    equiposFiltrados.value = equipos.value
  } catch (e) {
    console.error('Error al cargar equipos:', e)
  }
}

onMounted(cargarEquipos)

const parseCodigos = (text) =>
  text
    .split('\n')
    .map((c) => c.trim())
    .filter(Boolean)

const validarCodigos = async () => {
  const codigos = parseCodigos(codigosTexto.value)

  if (!codigos.length) {
    noEncontrados.value = []
    equiposFiltrados.value = equipos.value
    return
  }

  try {
    const { data } = await axios.post(`${apiBaseUrl}/validar-equipos`, { codigos })

    const encontrados = Array.isArray(data?.encontrados) ? data.encontrados : []
    noEncontrados.value = Array.isArray(data?.no_encontrados) ? data.no_encontrados : []

    // Si el backend ya devuelve objetos equipo, usarlos directo
    if (encontrados.length && typeof encontrados[0] === 'object') {
      equiposFiltrados.value = encontrados
      return
    }

    // Si devuelve códigos, filtramos desde la lista original
    const codigosEncontrados = encontrados.map((x) => (typeof x === 'string' ? x : x.codigo))
    equiposFiltrados.value = equipos.value.filter((e) => codigosEncontrados.includes(e.codigo))
  } catch (e) {
    console.error('Error al validar códigos:', e)
  }
}

const limpiarFiltros = () => {
  filtros.value = buildFiltros()
  estadoSeleccionado.value = 'Todos'
  tipoSeleccionado.value = 'Todos'
  rangoFechas.value = null
  noEncontrados.value = []
  equiposFiltrados.value = equipos.value
  codigosTexto.value = ''
}

/* Helpers */
const normalize = (v) => (v ?? '').toString().trim().toLowerCase()

const unique = (arr) =>
  Array.from(new Set(arr.map((v) => (v ?? '').toString().trim()).filter(Boolean)))

const estadoOpciones = computed(() => ['Todos', ...unique(equiposFiltrados.value.map((e) => e.estado))])
const tipoOpciones = computed(() => ['Todos', ...unique(equiposFiltrados.value.map((e) => e.tipo))])

/* Rango de fechas */
const startOfDay = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate())

const inRange = (dateValue, start, end) => {
  if (!start && !end) return true
  const d = startOfDay(dateValue).getTime()

  const s = start ? startOfDay(start).getTime() : null
  const e = end ? startOfDay(end).getTime() : null

  if (s && e) return d >= Math.min(s, e) && d <= Math.max(s, e)
  if (s) return d >= s
  return d <= e
}

const equiposVisibles = computed(() => {
  const estado = estadoSeleccionado.value
  const tipo = tipoSeleccionado.value
  const [inicio, fin] = Array.isArray(rangoFechas.value) ? rangoFechas.value : [null, null]

  return equiposFiltrados.value.filter((equipo) => {
    if (estado !== 'Todos' && normalize(equipo.estado) !== normalize(estado)) return false
    if (tipo !== 'Todos' && normalize(equipo.tipo) !== normalize(tipo)) return false

    if (inicio || fin) {
      const fecha = new Date(equipo.fecha_entrega)
      if (Number.isNaN(fecha.getTime())) return false
      if (!inRange(fecha, inicio, fin)) return false
    }

    return true
  })
})

/* Clase para ícono de filtro (simple) */
const getFilterHeaderClass = () => '' // opcional: lo puedes eliminar o dejar así

/* Estilos estado (simple, sin hashing) */
const estadoStyles = {
  entregado: { background: 'rgba(26,188,156,0.18)', color: '#0e6251', border: '1px solid rgba(26,188,156,0.5)' },
  pendiente: { background: 'rgba(243,156,18,0.2)', color: '#7d6608', border: '1px solid rgba(243,156,18,0.55)' },
  reparacion: { background: 'rgba(52,152,219,0.18)', color: '#1b4f72', border: '1px solid rgba(52,152,219,0.5)' },
  default: { background: 'rgba(236,240,241,0.9)', color: '#2c3e50', border: '1px solid rgba(189,195,199,0.6)' }
}

const getEstadoStyle = (estado) => {
  const key = normalize(estado)
  return estadoStyles[key] || estadoStyles.default
}
</script>


<template>
  <div class="page">
    <div class="frame">
      <header class="hero">
        <div class="hero-text">
          <span class="eyebrow">Panel</span>
          <h1>Gestión de equipos</h1>
          <p class="subtitle">
            Valida códigos masivos y aplica filtros por columna para revisar resultados.
          </p>
        </div>
      </header>

      <section class="card search-card">
        <div class="card-header">
          <div>
            <h2>Validación masiva</h2>
            <p>Pega los códigos uno por línea y valida su existencia.</p>
          </div>
        </div>
        <div class="search-body">
          <label for="codigos">Códigos</label>
          <Textarea
            id="codigos"
            v-model="codigosTexto"
            rows="6"
            autoResize
            placeholder="EQ1&#10;EQ2&#10;EQ3"
          />
          <div class="actions">
            <Button label="Validar códigos" icon="pi pi-check" @click="validarCodigos" />
            <Button
              label="Limpiar filtros"
              icon="pi pi-filter-slash"
              severity="secondary"
              @click="limpiarFiltros"
            />
          </div>
        </div>
 
        <Message class="message-box" v-if="noEncontrados.length" severity="error" :closable="false">
          Códigos no encontrados: {{ noEncontrados.join(', ') }}
        </Message>
      </section>

      <section class="card table-card">
        <div class="card-header">
          <div>
            <h2>Resultados</h2>
            <p>Usa los filtros para buscar por código, cliente o estado.</p>
          </div>
        </div>

        <div class="quick-filters">
          <div class="filter-group">
            <span class="filter-label">Estado</span>
            <div class="chip-row">
              <button
                v-for="estado in estadoOpciones"
                :key="estado"
                type="button"
                class="filter-chip"
                :class="{ 'is-active': estadoSeleccionado === estado }"
                @click="estadoSeleccionado = estado"
              >
                {{ estado }}
              </button>
            </div>
          </div>
          <div class="filter-group">
            <span class="filter-label">Tipo</span>
            <div class="chip-row">
              <button
                v-for="tipo in tipoOpciones"
                :key="tipo"
                type="button"
                class="filter-chip"
                :class="{ 'is-active': tipoSeleccionado === tipo }"
                @click="tipoSeleccionado = tipo"
              >
                {{ tipo }}
              </button>
            </div>
          </div>
          <div class="filter-group filter-range">
            <span class="filter-label">Rango de fechas</span>
            <Calendar
              v-model="rangoFechas"
              selectionMode="range"
              showIcon
              dateFormat="dd-mm-yy"
              placeholder="Selecciona rango"
              class="range-picker"
            />
            <button
              v-if="Array.isArray(rangoFechas) && rangoFechas.length"
              type="button"
              class="filter-chip subtle"
              @click="rangoFechas = null"
            >
              Quitar rango
            </button>
          </div>
        </div>

        <!-- Armado de la tabla -->

        <DataTable
          class="tabla"
          :value="equiposVisibles"
          v-model:filters="filtros"
          filterDisplay="row"
          paginator
          :rows="10"
          responsiveLayout="scroll"
        >
        <template #empty>
            <div class="tabla-vacia">
              <i class="pi pi-search" style="font-size: 2rem; margin-bottom: 10px;"></i>
              <p>No hay coincidencias con los filtros aplicados.</p>
            </div>
          </template>
          <Column field="codigo" header="Codigo" :filterHeaderClass="getFilterHeaderClass('codigo')">
            <template #filter>
              <InputText v-model="filtros.codigo.value" placeholder="Filtrar" />
            </template>
          </Column>
          <Column field="cliente" header="Cliente" :filterHeaderClass="getFilterHeaderClass('cliente')">
            <template #filter>
              <InputText v-model="filtros.cliente.value" placeholder="Filtrar" />
            </template>
          </Column>
          <Column field="tipo" header="Tipo" :filterHeaderClass="getFilterHeaderClass('tipo')">
            <template #filter>
              <InputText v-model="filtros.tipo.value" placeholder="Filtrar" />
            </template>
          </Column>
          <Column field="estado" header="Estado" :filterHeaderClass="getFilterHeaderClass('estado')">
            <template #body="{ data }">
              <span class="status-tag" :style="getEstadoStyle(data.estado)">
                {{ data.estado || 'Sin estado' }}
              </span>
            </template>
            <template #filter>
              <InputText v-model="filtros.estado.value" placeholder="Filtrar" />
            </template>
          </Column>
          <Column field="fecha_entrega" header="Entrega" :filterHeaderClass="getFilterHeaderClass('fecha_entrega')">
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                @input="filterCallback()"
                placeholder="Buscar"
                class="p-column-filter"
              />
            </template>

            <template #body="{ data }">
              <span v-if="data.fecha_entrega">
                {{ data.fecha_entrega }}
              </span>
              <span v-else class="muted">Sin entrega</span>
            </template>
          </Column>
        </DataTable>
      </section>
    </div>
  </div>
</template>