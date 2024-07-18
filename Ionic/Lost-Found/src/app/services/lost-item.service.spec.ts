import { TestBed } from '@angular/core/testing';

import { LostItemService } from './lost-item.service';

describe('LostItemService', () => {
  let service: LostItemService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LostItemService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
