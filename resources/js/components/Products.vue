<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-semibold text-gray-900">Productos</h1>
          <p class="text-sm text-gray-500 mt-1">Gestiona tu catálogo de productos</p>
        </div>
        <button
          @click="openModal"
          class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
        >
          <Plus :size="18" />
          Nuevo Producto
        </button>
      </div>

      <ProductTable
        :products="products"
        :loading="loading"
        @edit="editProduct"
        @delete="confirmDelete"
      />

      <ProductModal
        :show="showModal"
        :editing-id="editingId"
        :initial-data="form"
        :saving="saving"
        @close="closeModal"
        @submit="saveProduct"
      />

      <Transition name="modal">
        <div
          v-if="showDeleteModal"
          class="fixed inset-0 z-50 flex items-center justify-center p-4"
          @click.self="showDeleteModal = false"
        >
          <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showDeleteModal = false"></div>
          
          <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-start gap-4 mb-4">
              <div class="shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <AlertTriangle :size="20" class="text-red-600" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Eliminar Producto</h3>
                <p class="text-sm text-gray-600">Esta acción no se puede deshacer</p>
              </div>
            </div>
            <p class="text-sm text-gray-700 mb-6">
              ¿Estás seguro de que deseas eliminar este producto?
            </p>
            <div class="flex gap-3">
              <button
                @click="deleteProduct"
                :disabled="deleting"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 disabled:bg-red-400 disabled:cursor-not-allowed transition-colors"
              >
                <Loader2 v-if="deleting" :size="16" class="animate-spin" />
                <Trash2 v-else :size="16" />
                {{ deleting ? 'Eliminando...' : 'Eliminar' }}
              </button>
              <button
                @click="showDeleteModal = false"
                :disabled="deleting"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 disabled:bg-gray-50 disabled:cursor-not-allowed transition-colors"
              >
                Cancelar
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ProductTable from './ProductTable.vue';
import ProductModal from './ProductModal.vue';
import { AlertTriangle, Loader2, Trash2, Plus } from 'lucide-vue-next';

const products = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingId = ref(null);
const productToDelete = ref(null);

const form = ref({
  name: '',
  price: '',
  description: ''
});

const loadProducts = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/products');
    if (response.data.status === 200) {
      products.value = response.data.data;
    }
  } catch (error) {
    console.error('Error al cargar productos:', error);
  } finally {
    loading.value = false;
  }
};

const openModal = () => {
  resetForm();
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const resetForm = () => {
  form.value = {
    name: '',
    price: '',
    description: ''
  };
  editingId.value = null;
};

const editProduct = (product) => {
  editingId.value = product.id;
  form.value = {
    name: product.name,
    price: product.price,
    description: product.description
  };
  showModal.value = true;
};

const saveProduct = async (formData) => {
  saving.value = true;
  try {
    if (editingId.value) {
      const response = await axios.put(`/api/products/${editingId.value}`, formData);
      if (response.data.status === 200) {
        await loadProducts();
        closeModal();
      }
    } else {
      const response = await axios.post('/api/products', formData);
      if (response.data.status === 200) {
        await loadProducts();
        closeModal();
      }
    }
  } catch (error) {
    console.error('Error al guardar producto:', error);
  } finally {
    saving.value = false;
  }
};

const confirmDelete = (id) => {
  productToDelete.value = id;
  showDeleteModal.value = true;
};

const deleteProduct = async () => {
  deleting.value = true;
  try {
    const response = await axios.delete(`/api/products/${productToDelete.value}`);
    if (response.data.status === 200) {
      await loadProducts();
      showDeleteModal.value = false;
      productToDelete.value = null;
    }
  } catch (error) {
    console.error('Error al eliminar producto:', error);
  } finally {
    deleting.value = false;
  }
};

onMounted(() => {
  loadProducts();
});
</script>
