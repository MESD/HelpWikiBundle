<?php
/**
 * PageRepository.php file
 *
 * File that contains the help wiki page entity repository class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Entity/PageRepository.php
 * @package    Mesd\HelpWikiBundle\Entity
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{

    public function getPagesNotEqualToPage($pageId)
    {
        // get all the pages not equal to the page passed
        // used to create a select box for creating page associations
        return $this->createQueryBuilder('p')
            ->where('p.id <> ?1')
            ->orderBy('p.title')
            ->setParameter(1, $pageId)
        ;
    }

    public function getParentPage($pageId)
    {
        // this has to happen with the query

        //    select p.*
        //      from helpwiki_page q
        //inner join helpwiki_page p
        //        on q.parent_id = p.id
        //     where q.id = 4
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('\Mesd\HelpWikiBundle\Entity\Page', 'q', 'WITH', 'q.parent = p.id')
            ->where($qb->expr()->eq('q.id', ':childId'))
            ->setParameter('childId', $pageId)
        ;

        $q = $qb->getQuery();

        return $q->getResult();
    }

    public function getPreviousPage($pageId)
    {
        // the the previous page, like a book

        // if there isn't a previous page
        // get the last page of the previous chapter
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('\Mesd\HelpWikiBundle\Entity\Page', 'q', 'WITH', 'q.parent = p.id')
            ->where(
                $qb->expr()->eq('q.id', ':childId'))
            ->setParameter('childId', $pageId)
        ;

        $q = $qb->getQuery();

        return $q->getResult();
    }

    public function getNextPage(Page $page)
    {
        // get the next page, like a book

        // set the printOrder var to the current
        // printOrder and increment it by 1
        // that means this is the next page
        $printOrder = $page->getPrintOrder() + 1;
        $parentId   = $page->getParent();

        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->eq('p.printOrder', ':printOrder'));
        
        // if there isn't a parent page,
        // then we are at the top level of pages
        // so we should modify the query as necessary
        if(empty($parentId)) {
            $qb->andWhere($qb->expr()->isNull('p.parent'));
        } else {
            $qb->andWhere(
                $qb
                    ->expr()->eq('p.parent', ':parentId'))
                    ->setParameter('parentId', $parentId);
        }
        
        $qb->setParameter('printOrder', $printOrder);

        $q = $qb->getQuery();

        $result = $q->getResult();

        // if there isn't another page properly
        // assigned a printOrder, then we should
        // look for the next alphabetically,
        // but this could lead to problems where
        // there is recursion because to pages
        // might infinitely lead back to each
        // other over and over

        // if there isn't another page,
        // lets get the next parent page
        if(empty($result) && !empty($parentId)) {

            return 'party party foobar';
        }

        // if there isn't even that,
        // then we're on the last page,
        // we should return false

        return $q->getResult();
    }
}
