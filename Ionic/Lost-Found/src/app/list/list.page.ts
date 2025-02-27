import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule, NavController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';

interface Item {
  image_path: string;
  item_name: string;
  category: string;
  description: string;
  date_found: string;
  finder_name: string;
  finder_contact: string;
}

@Component({
  selector: 'app-list',
  templateUrl: './list.page.html',
  styleUrls: ['./list.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
})
export class ListPage implements OnInit {
  items: Item[] = [];
  paginatedItems: Item[] = [];
  selectedItem: Item | null = null;
  currentPage = 1;
  itemsPerPage = 10;
  totalPages = 1;
  searchTerm: string = '';

  constructor(
    private http: HttpClient,
    private navCtrl: NavController,
    private route: ActivatedRoute
  ) {}

  ngOnInit() {
    this.route.queryParams.subscribe((params: any) => {
      const itemId = params['itemId'];
      if (itemId) {
        this.loadItemDetails(itemId);
      }
    });

    this.fetchItems();
  }

  fetchItems() {
    this.http.get<Item[]>('http://192.168.4.169:80/getItem.php').subscribe(
      (data) => {
        console.log('Items fetched:', data); // Check if these fields are present
        this.items = data.map(item => ({
          ...item,
          image_path: `http://192.168.4.169:80/${item.image_path}` // Ensure the path is correct
        }));
        this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);
        this.paginateItems();
      },
      (error) => {
        console.error('Error fetching items:', error);
      }
    );
  }
  

  paginateItems() {
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;
    this.paginatedItems = this.items.slice(start, end);
    console.log('Paginated items:', this.paginatedItems); // Debugging line
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.paginateItems();
    }
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.paginateItems();
    }
  }

  loadItemDetails(itemId: string) {
    this.http.get<Item>(`http://192.168.4.169/getItem.php?id=${itemId}`).subscribe(
      (data) => {
        console.log('Item details:', data); // Debugging line
        this.selectedItem = data;
      },
      (error) => {
        console.error('Error fetching item details:', error);
      }
    );
  }

  filterItems() {
    // Implement the filtering logic based on `searchTerm`
    this.paginatedItems = this.items.filter(item => 
      item.item_name.toLowerCase().includes(this.searchTerm.toLowerCase())
    );
  }
}
