<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const open = ref(false)
const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)
const user = computed(() => page.props.auth?.user)

function toggle(){ open.value = !open.value }
function close(){ open.value = false }
</script>

<template>
  <header class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white sticky top-0 z-50 shadow-lg">
    <div class="mx-auto max-w-7xl px-3 md:px-6">
      <!-- top bar -->
      <div class="flex h-16 items-center justify-between gap-3">
        <!-- brand -->
        <Link href="/" class="flex items-center gap-2 min-w-0">
          <span class="text-2xl">🏝️</span>
          <div class="leading-tight min-w-0">
            <div class="font-bold text-sm md:text-xl whitespace-nowrap truncate">
              日本人向けバリ島不動産サイト
            </div>
            <div class="text-[10px] md:text-xs opacity-90 whitespace-nowrap truncate">
              Bali Real Estate for Japanese
            </div>
          </div>
        </Link>

        <!-- desktop nav -->
        <nav class="hidden md:flex items-center gap-5 text-sm">
          <Link href="/" class="hover:text-blue-200 whitespace-nowrap">物件一覧</Link>
          
          <!-- 購入希望者メニュー -->
          <template v-if="isAuthenticated && user?.role === 'user'">
            <Link href="/favorites" class="hover:text-blue-200 whitespace-nowrap">お気に入り</Link>
            <Link href="/user/inquiries" class="hover:text-blue-200 whitespace-nowrap">お問い合わせ履歴</Link>
          </template>
          
          <!-- エージェントメニュー -->
          <template v-if="isAuthenticated && user?.role === 'agent'">
            <Link href="/agent/dashboard" class="hover:text-blue-200 whitespace-nowrap">ダッシュボード</Link>
            <Link href="/agent/properties" class="hover:text-blue-200 whitespace-nowrap">物件管理</Link>
            <Link href="/agent/properties/create" class="hover:text-blue-200 whitespace-nowrap">物件登録</Link>
            <Link href="/agent/reports" class="hover:text-blue-200 whitespace-nowrap">レポート</Link>
          </template>
          
          <Link v-if="isAuthenticated" href="/dashboard" class="hover:text-blue-200 whitespace-nowrap">マイページ</Link>
          
          <!-- ログインボタン -->
          <template v-if="!isAuthenticated">
            <Link href="/login/buyer" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 rounded-full text-xs whitespace-nowrap">
              🏠 購入希望者ログイン
            </Link>
            <Link href="/login/agent" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 rounded-full text-xs whitespace-nowrap">
              🏢 エージェントログイン
            </Link>
          </template>
          
          <Link v-if="isAuthenticated" href="/logout" method="post" as="button" class="hover:text-blue-200 whitespace-nowrap">ログアウト</Link>
        </nav>

        <!-- mobile toggle -->
        <button class="md:hidden inline-flex items-center justify-center rounded-lg p-2 hover:bg-white/10"
                @click="toggle" aria-label="Toggle menu">
          <svg v-if="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- mobile menu -->
      <div v-show="open" class="md:hidden pb-3" @click.self="close">
        <nav class="flex flex-col gap-1 text-sm">
          <Link href="/" class="px-3 py-2 rounded hover:bg-white/10" @click="close">物件一覧</Link>
          
          <!-- 購入希望者メニュー -->
          <template v-if="isAuthenticated && user?.role === 'user'">
            <Link href="/favorites" class="px-3 py-2 rounded hover:bg-white/10" @click="close">お気に入り</Link>
            <Link href="/user/inquiries" class="px-3 py-2 rounded hover:bg-white/10" @click="close">お問い合わせ履歴</Link>
          </template>
          
          <!-- エージェントメニュー -->
          <template v-if="isAuthenticated && user?.role === 'agent'">
            <Link href="/agent/dashboard" class="px-3 py-2 rounded hover:bg-white/10" @click="close">ダッシュボード</Link>
            <Link href="/agent/properties" class="px-3 py-2 rounded hover:bg-white/10" @click="close">物件管理</Link>
            <Link href="/agent/properties/create" class="px-3 py-2 rounded hover:bg-white/10" @click="close">物件登録</Link>
            <Link href="/agent/reports" class="px-3 py-2 rounded hover:bg-white/10" @click="close">レポート</Link>
          </template>
          
          <Link v-if="isAuthenticated" href="/dashboard" class="px-3 py-2 rounded hover:bg-white/10" @click="close">マイページ</Link>
          
          <!-- ログイン/登録ボタン -->
          <template v-if="!isAuthenticated">
            <div class="px-3 py-2 space-y-2">
              <Link href="/login/buyer" class="block w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 rounded-full text-center text-xs" @click="close">
                🏠 購入希望者ログイン
              </Link>
              <Link href="/login/agent" class="block w-full px-3 py-2 bg-green-500 hover:bg-green-600 rounded-full text-center text-xs" @click="close">
                🏢 エージェントログイン
              </Link>
              <div class="flex gap-2 mt-2">
                <Link href="/register/buyer" class="flex-1 px-3 py-1.5 bg-white text-blue-600 hover:bg-blue-50 rounded-full text-center text-xs" @click="close">
                  購入希望者登録
                </Link>
                <Link href="/register/agent" class="flex-1 px-3 py-1.5 bg-white text-green-600 hover:bg-green-50 rounded-full text-center text-xs" @click="close">
                  エージェント登録
                </Link>
              </div>
            </div>
          </template>
          
          <Link v-if="isAuthenticated" href="/logout" method="post" as="button" class="text-left px-3 py-2 rounded hover:bg-white/10" @click="close">ログアウト</Link>
        </nav>
      </div>
    </div>
  </header>
</template>
