# UI Components

## Badge
Used for status indicators and tags:
```vue
<Badge variant="green">Active</Badge>
<Badge variant="red">Inactive</Badge>
<Badge variant="blue">New</Badge>
```

## Pagination
For paginated content:
```vue
<Pagination 
  :page="currentPage" 
  :last-page="totalPages" 
  :links="{ prev: prevUrl, next: nextUrl }"
  @change="handlePageChange" 
/>
```

## EmptyState
For empty data states:
```vue
<EmptyState 
  title="No properties found" 
  description="Try adjusting your search criteria"
>
  <button>Add New Property</button>
</EmptyState>
```

## Loading
Simple loading indicator:
```vue
<Loading />
```

## Property ListToolbar
Sorting and filtering toolbar for property lists:
```vue
<ListToolbar 
  :sort="currentSort"
  :order="currentOrder" 
  :per-page="perPage"
  :total="totalCount"
  @change="handleToolbarChange"
/>
```