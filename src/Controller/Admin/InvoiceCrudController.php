<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\Invoice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InvoiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invoice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInPlural(' Invoices')
            ->setEntityLabelInSingular('Invoice')
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('client')
                ->autocomplete(
                    callback: static fn (Client $c): string => $c->getName()
                ),
            TextField::new('number'),
            DateTimeField::new('issueDate'),
            DateTimeField::new('dueDate'),
            CurrencyField::new('currency'),
            MoneyField::new('totalAmountMinor')
                ->setCurrencyPropertyPath('currency')
                ->setStoredAsCents(),
            MoneyField::new('paidAmountMinor')
                ->setCurrencyPropertyPath('currency')
                ->setStoredAsCents(),
            ChoiceField::new('status'),
            TextEditorField::new('notes'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
