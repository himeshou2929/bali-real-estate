<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)
const user = computed(() => page.props.auth?.user)
</script>

<template>
  <header class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <!-- サイトタイトル -->
        <div class="flex items-center">
          <Link href="/" class="flex items-center space-x-3">
            <span class="text-2xl">🏝️</span>
            <div>
              <h1 class="text-xl font-bold">日本人向けバリ島不動産サイト</h1>
              <p class="text-xs text-blue-100">Bali Real Estate for Japanese</p>
            </div>
          </Link>
        </div>

        <!-- 右側のナビゲーション -->
        <div class="flex items-center gap-4">
          <!-- ログイン済みの場合 -->
          <div v-if="isAuthenticated" class="flex items-center gap-4">
            <span class="text-sm">{{ user.name }}</span>
            <Link href="/dashboard" class="text-sm hover:text-blue-200">マイページ</Link>
            <Link href="/logout" method="post" as="button" class="text-sm hover:text-blue-200">ログアウト</Link>
          </div>
          
          <!-- 未ログインの場合 -->
          <div v-else class="flex items-center gap-3">
            <!-- 購入希望者向け -->
            <div class="flex flex-col gap-1">
              <Link 
                href="/login/buyer" 
                class="inline-flex items-center px-2 md:px-3 py-1.5 text-xs bg-blue-500 text-white rounded-full hover:bg-blue-600 transition"
              >
                🏠 <span class="hidden sm:inline">購入希望者</span>ログイン
              </Link>
              <Link 
                href="/register/buyer" 
                class="inline-flex items-center px-2 md:px-3 py-1.5 text-xs bg-white text-blue-600 rounded-full hover:bg-blue-50 transition border border-blue-200"
              >
                <span class="hidden sm:inline">購入希望者</span>登録
              </Link>
            </div>
            <!-- エージェント向け -->
            <div class="flex flex-col gap-1">
              <Link 
                href="/login/agent" 
                class="inline-flex items-center px-2 md:px-3 py-1.5 text-xs bg-green-500 text-white rounded-full hover:bg-green-600 transition"
              >
                🏢 <span class="hidden sm:inline">エージェント</span>ログイン
              </Link>
              <Link 
                href="/register/agent" 
                class="inline-flex items-center px-2 md:px-3 py-1.5 text-xs bg-white text-green-600 rounded-full hover:bg-green-50 transition border border-green-200"
              >
                <span class="hidden sm:inline">エージェント</span>登録
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- サブヘッダー（ナビゲーション） -->
  <nav class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center gap-6 h-12">
        <Link href="/" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url === '/' }">
          物件一覧
        </Link>
        
        <!-- 購入希望者向けメニュー -->
        <template v-if="isAuthenticated && user?.role === 'user'">
          <Link href="/favorites" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url === '/favorites' }">
            お気に入り
          </Link>
          <Link href="/user/inquiries" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url.startsWith('/user/inquiries') }">
            お問い合わせ履歴
          </Link>
        </template>
        
        <!-- エージェント向けメニュー -->
        <template v-if="isAuthenticated && user?.role === 'agent'">
          <Link href="/agent/dashboard" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url === '/agent/dashboard' }">
            ダッシュボード
          </Link>
          <Link href="/agent/properties" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url.startsWith('/agent/properties') }">
            物件管理
          </Link>
          <Link href="/agent/properties/create" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url === '/agent/properties/create' }">
            物件登録
          </Link>
          <Link href="/agent/reports" class="text-sm font-medium hover:text-blue-600" :class="{ 'text-blue-600': $page.url === '/agent/reports' }">
            レポート
          </Link>
        </template>
      </div>
    </div>
  </nav>
</template>