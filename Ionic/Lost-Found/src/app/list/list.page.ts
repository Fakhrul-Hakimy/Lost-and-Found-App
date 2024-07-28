import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule, NavController } from '@ionic/angular';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-list',
  templateUrl: './list.page.html',
  styleUrls: ['./list.page.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, HttpClientModule],
})
export class ListPage implements OnInit {
  items: any[] = [];
  paginatedItems: any[] = [];
  selectedItem: any;
  currentPage: number = 1;
  itemsPerPage: number = 10;
  totalPages: number = 0;

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
    this.http.get<any[]>('http://localhost/getItem.php').subscribe(
      (data) => {
        console.log('Items fetched:', data); // Debugging line
        this.items = data.map(item => ({
          ...item,
          image_path: `http://localhost/${item.image_path}` // Ensure the path is correct
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
    this.http.get<any>(`http://localhost/getItem.php?id=${itemId}`).subscribe(
      (data) => {
        console.log('Item details:', data); // Debugging line
        this.selectedItem = data;
      },
      (error) => {
        console.error('Error fetching item details:', error);
      }
    );
  }
}
