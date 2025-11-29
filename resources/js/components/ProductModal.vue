<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.self="close"
      >
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="close"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">
              {{ editingId ? 'Editar Producto' : 'Nuevo Producto' }}
            </h3>
            <button
              @click="close"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <X :size="20" />
            </button>
          </div>

          <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Nombre
              </label>
              <input
                v-model="form.name"
                type="text"
                placeholder="Nombre del producto"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Precio
              </label>
              <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                placeholder="0.00"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Descripción
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Descripción del producto"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 resize-none"
              ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
              <button
                type="submit"
                :disabled="saving"
                class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed transition-colors"
              >
                <span v-if="saving" class="flex items-center justify-center gap-2">
                  <Loader2 :size="16" class="animate-spin" />
                  Guardando...
                </span>
                <span v-else>{{ editingId ? 'Actualizar' : 'Crear' }}</span>
              </button>
              <button
                type="button"
                @click="close"
                :disabled="saving"
                class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-md hover:bg-gray-200 disabled:bg-gray-50 disabled:cursor-not-allowed transition-colors"
              >
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { X, Loader2 } from 'lucide-vue-next';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  editingId: {
    type: Number,
    default: null
  },
  initialData: {
    type: Object,
    default: () => ({ name: '', price: '', description: '' })
  },
  saving: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'submit']);

const form = ref({
  name: '',
  price: '',
  description: ''
});

watch(() => props.show, (newVal) => {
  if (newVal) {
    form.value = {
      name: props.initialData.name || '',
      price: props.initialData.price || '',
      description: props.initialData.description || ''
    };
  }
});

watch(() => props.initialData, (newData) => {
  if (props.show) {
    form.value = {
      name: newData.name || '',
      price: newData.price || '',
      description: newData.description || ''
    };
  }
}, { deep: true });

const close = () => {
  if (!props.saving) {
    emit('close');
  }
};

const handleSubmit = () => {
  emit('submit', { ...form.value });
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.95);
  opacity: 0;
}
</style>

